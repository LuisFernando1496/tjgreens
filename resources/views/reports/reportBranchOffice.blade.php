<html>
    <head>
        <style type="text/css">
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
                text-align: center;
                border-color: #424242;
                font-size: 12px;
            }
            .backgroundColor{
                background: red;
            }
        </style>
    </head> 
    @php
        $totalVentasGeneral = 0;
        $totalTarjeta = 0;
        $totalEfectivo = 0;
        $totalCredito = 0;
        $totalMixto = 0;
        $totaldia = 0;
        $totalmes = 0;
        $totalcosto = 0;
        $totalprecio = 0;
    @endphp
   
    
    <body>
        <div style="text-align:center; margin-left: auto; margin-right: auto;">
           
            <h4 >REPORTE DE VENTAS</h4>
            <h5>DESDE {{$from}} HASTA {{$to}}</h5>

            @foreach ($branchOffice as $b)
            @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
            <table style="width: 100%; margin-top:20px;">
                <tr>
                    <th colspan="12" class="backgroundColor">
                        SUCURSAL
                    </th>
                </tr>
                <tr>
                    <td colspan="12">
                        {{$b->name}}
                    </td>
                </tr>
            @else
            <table style="width: 100%; margin-top:20px;">
                <tr>
                    <th colspan="10" class="backgroundColor">
                        SUCURSAL
                    </th>
                </tr>
                <tr>
                    <td colspan="10">
                        {{$b->name}}
                    </td>
                </tr>
            @endif
                <tr>
                  
                    <th style="font-size: 10px" class="backgroundColor">PRODUCTO</th>
                    <th style="font-size: 10px" class="backgroundColor">CATEGORÍA</th>
                    <th style="font-size: 10px" class="backgroundColor">MARCA</th>
                    <th style="font-size: 10px" class="backgroundColor">CANTIDAD</th>
                    @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                    <th style="font-size: 10px" class="backgroundColor">COSTO</th>  
                    @endif
                    <th style="font-size: 10px" class="backgroundColor">PRECIO <br/> PÚBLICO</th>
                    <th style="font-size: 10px" class="backgroundColor">DESCUENTO</th>

                    @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                    <th style="font-size: 10px" class="backgroundColor">INVERSION</th>  
                    @endif
                    <th style="font-size: 10px" class="backgroundColor">TOTAL</th>
                    <th style="font-size: 10px" class="backgroundColor">VENDEDOR</th>
                    <th style="font-size: 10px" class="backgroundColor">FECHA</th>
                    <th style="font-size: 10px" class="backgroundColor">HORA</th>
                </tr>
               
                {{--@foreach ($products as $p)--}}
                @foreach ($products as $iterador => $p)
                    @if ($b->id != $p->branch_office_id )
                        @if($iterador < sizeof($products)-1)
                            @if(date('d',strtotime($products[$iterador]->date)) != date('d',strtotime($products[$iterador+1]->date)))
                                @if($totaldia != 0)
                                <tr>
                                    <td colspan="3">Venta total del día {{$totaldia}}</td>
                                    <td colspan="4">Venta Costos por día {{$totalcosto}}</td>
                                    <td colspan="4">Venta total precio al publico {{$totalprecio}}</td>
                                </tr>
                                    @php
                                        $totaldia = 0;
                                        $totalcosto = 0;
                                        $totalprecio = 0;
                                    @endphp
                                @endif
                            @endif
                        @endif
                        
                        @if($iterador == sizeof($products)-1)
                            @if($totaldia != 0)
                                <tr>
                                    <td colspan="3">Venta final del día {{$totaldia}}</td>
                                    <td colspan="4">Venta Costos por día {{$totalcosto}}</td>
                                    <td colspan="4">Venta total precio al publico {{$totalprecio}}</td>
                                </tr>
                            @endif
                            
                            <tr>
                                <td colspan="12">Venta total del mes {{$totalmes}}</td>
                            </tr>
                            @php
                                $totalmes = 0;
                            @endphp
                            
                            @php
                                $totaldia = 0;
                                $totalcosto = 0;
                                $totalprecio = 0;
                            @endphp
                        @endif

                        @if($iterador < sizeof($products)-1)
                            @if(date('m',strtotime($products[$iterador]->date)) != date('m',strtotime($products[$iterador+1]->date)))
                                @if($totalmes != 0)
                                <tr>
                                    <td colspan="12">Venta total del mes {{$totalmes}}</td>
                                </tr>
                                
                                    @php
                                        $totalmes = 0;
                                    @endphp    
                                @endif
                            @endif
                        @endif

                    @endif

          
                    @if ($b->id == $p->branch_office_id )
                        @php
                            $totaldia += $p->total;
                            $totalmes += $p->total;
                            $totalcosto += $p->cost;
                            $totalprecio += $p->sale_price;
                            $totalVentasGeneral += $p->total;
                            if($p->tipoPago == 0){
                                $totalEfectivo += $p->total;
                            }
                            if($p->tipoPago == 1){
                            $totalTarjeta += ( $p->total);
                            }
                            if($p->tipoPago == 2){
                                $totalCredito +=  $p->total;
                            }
                            if($p->tipoPago == 3){
                                $totalMixto +=  $p->total;
                            }
                        @endphp
                    
                    <tr>
                        <td>{{$p->product_name}}</td>
                        <td>{{$p->category}}</td>
                        @if ($p->brand == null)
                        <td>N/A</td>
                        @else
                        <td>{{$p->brand }}</td>
                        @endif

                        <td>{{$p->quantity}}</td>
                        @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                        <td>{{number_format($p->cost, 2)}}</td>
                        @endif
                        <td>{{number_format($p->sale_price, 2)}}</td>
                        <td>{{number_format($p->amount_discount * $p->quantity, 2)}}</td>
                        @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                        <td>{{number_format($p->cost * $p->quantity, 2)}}</td>
                        @endif
                        <td>{{number_format($p->total, 2)}}</td> 
                        <td>{{$p->seller.' '.$p->seller_lastName}}</td> 
                        <td>{{date('Y-m-d',strtotime($p->date))}}</td> 
                        <td>{{date('H:m:s',strtotime($p->date))}}</td> 


                    </tr>

                        @if($iterador < sizeof($products)-1)
                            @if(date('d',strtotime($products[$iterador]->date)) != date('d',strtotime($products[$iterador+1]->date)))
                            <tr>
                                <td colspan="4">Venta total del día {{$totaldia}}</td>
                                <td colspan="4">Costos por día {{$totalcosto}}</td>
                                <td colspan="4">Total precio al publico {{$totalprecio}}</td>
                            </tr>
                                @php
                                    $totaldia = 0;
                                    $totalcosto = 0;
                                    $totalprecio = 0;
                                @endphp    
                            @endif
                        @endif
                        @if($iterador == sizeof($products)-1)
                            <tr>
                                <td colspan="3">Venta final del día {{$totaldia}}</td>
                                <td colspan="4">Costos final por día {{$totalcosto}}</td>
                                <td colspan="4">precio al publico final {{$totalprecio}}</td>
                            </tr>
                            <tr>
                                <td colspan="12">Venta total del mes {{$totalmes}}</td>
                            </tr>
                        @endif
                        @if($iterador < sizeof($products)-1)
                            @if(date('m',strtotime($products[$iterador]->date)) != date('m',strtotime($products[$iterador+1]->date)))
                            <tr>
                                <td colspan="12">Venta total del mes {{$totalmes}}</td>
                            </tr>
                                @php
                                    $totalmes = 0;
                                @endphp    
                            @endif
                        @endif
                    @endif
                @endforeach
            </table>
            @endforeach
            <table style="width: 100%; margin-top:20px;">
                @if (Auth::user()->rol_id == 1)
                    
                <tr>
                    <th colspan="11" class="backgroundColor">
                        SUCURSAL
                    </th>
                </tr>
                <tr>
                    <td colspan="11">
                        RESUMEN GENERAL
                    </td>
                </tr>
                @else
                
              
                @endif
                <tr>
                    <th style="font-size: 10px" class="backgroundColor">#</th>
                    <th style="font-size: 10px" class="backgroundColor">PRODUCTO</th>
                    <th style="font-size: 10px" class="backgroundColor">CATEGORÍA</th>
                    <th style="font-size: 10px" class="backgroundColor">MARCA</th>
                    <th style="font-size: 10px" class="backgroundColor">CANTIDAD</th>
                    @if (Auth::user()->rol_id == 1 )
                    <th style="font-size: 10px" class="backgroundColor">COSTO</th>  
                    @endif
                    <th style="font-size: 10px" class="backgroundColor">PRECIO <br/> PÚBLICO</th>
                    <th style="font-size: 10px" class="backgroundColor">DESCUENTO</th>

                    @if (Auth::user()->rol_id == 1 )
                    <th style="font-size: 10px" class="backgroundColor">INVERSION</th>  
                    @endif
                    <th style="font-size: 10px" class="backgroundColor">TOTAL</th>
                    <th style="font-size: 10px" class="backgroundColor">VENDEDOR</th>
                </tr>

                @php
                    $k = 0;
                    $totalProduct = 0;
                    $total =0;
                @endphp
                @foreach ($ap as $p)
                @php
                    $k++;
                    $totalProduct += $p->quantity;
                    $total += ($p->cost * $p->quantity);
                @endphp
                <tr>
                    <td>{{$k}}</td>
                    <td>{{$p->product_name}}</td>
                    <td>{{$p->category}}</td>
                    @if ($p->brand == null)
                    <td>N/A</td>
                    @else
                    <td>{{$p->brand }}</td>
                    @endif

                    <td>{{$p->quantity}}</td>
                    @if (Auth::user()->rol_id == 1 )
                    <td>{{$p->cost}}</td>
                    @endif
                    <td>{{number_format($p->sale_price, 2)}}</td>
                    <td>{{number_format($p->amount_discount * $p->quantity, 2)}}</td>
                    @if (Auth::user()->rol_id == 1 )
                    <td>{{number_format(($p->cost * $p->quantity), 2)}}</td>
                    @endif
                    <td>{{number_format($p->total, 2)}}</td>  
                    <td>{{$p->seller.' '.$p->seller_lastName}}</td> 
                    {{-- <td>{{date('Y-m-d',strtotime($p->date))}}</td> 
                    <td>{{date('H:m:s',strtotime($p->date))}}</td>  --}}


                </tr>            
                @endforeach
            </table>

            <table style="width: 100%; margin-top:20px;">
                
                @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                <tr>
                    <tr>
                        <th colspan="4">TOTAL DE PRODUCTOS VENDIDOS</th>
                        <td >{{$totalProduct}}</td>
                    
                    <th colspan="2">TOTAL VENTAS</th>
                    <td >{{number_format( $totalVentasGeneral - $totalCredito, 2)}}</td>
                    {{-- <th>INVERSIÓN</th>
                    <td>${{$cash->costo + $card->costo}}</td> --}}

                </tr>
                @else
               
                <tr>
                    <th colspan="3">TOTAL VENTAS</th>
                    <td colspan="3">{{number_format($totalVentasGeneral - $totalCredito, 2)}}</td>
                </tr>
                @endif
                <tr>
                    <th colspan="3">TOTAL EN CREDITO</th>
                    <td colspan="3">{{$totalCredito }}</td>
                </tr>
                <tr>
                    <th colspan="3">DINERO EFECTIVO</th>
                    <td colspan="3">{{number_format($totalEfectivo,2) }}</td>
                </tr>
                <tr>
                    <th colspan="3">PAGO MIXTO</th>
                    <td colspan="3">{{number_format($totalMixto,2) }}</td>
                </tr>
                
                <tr>
                    <th colspan="3">DINERO ELECTRÓNICO</th>
                    <td colspan="3">{{number_format($totalTarjeta, 2)}}</td>
                </tr>
                <tr>
                   
                    <th colspan="3">DESCUENTOS</th>
                    <td colspan="3">{{number_format($cash->descuento + $card->descuento, 2)}}</td>
                </tr>
               
            </table>

           

            <h5 style="margin: 20px;">REPORTE GENERADO POR {{strtoupper($user->name)}}</h5>
            <h5 style="margin: 5px;">{{$date}}</h5>
            
        </div>
    </body>
</html>