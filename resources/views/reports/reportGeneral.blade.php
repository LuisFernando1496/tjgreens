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
    <body>
        @php
            $totalVendido = 0;
            $totaldia = 0;
            $totalmes = 0;
        @endphp
        <div style="text-align:center; margin-left: auto; margin-right: auto;">
{{--             
            <table style="width: 100%; margin-top:20px;">
                <tr>
                    <th colspan="1" style=" border-color: transparent" >
                        <img  src="{{ public_path('logopdf.png') }}" width="150px;">
                    </th>
                    <th colspan="4" style=" border-color: transparent" >
                        <h4 style="padding-right: 15em">REPORTE DE VENTAS</h4>
                    </th>
                </tr>

            </table> --}}

            <h4 >REPORTE DE VENTAS</h4>
            <h5>DESDE {{$from}} HASTA {{$to}}</h5>
            @foreach ($branchOffice as $b)
            <table style="width: 100%; margin-top:20px;">
                @if (Auth::user()->rol_id == 1)
                    
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
                    <th style="font-size: 10px" class="backgroundColor">FECHA</th>
                    <th style="font-size: 10px" class="backgroundColor">HORA</th>
                </tr>
                @foreach ($products as $iterador => $p)
                    @if ($b->id != $p->branch_office_id )
                        @if($iterador < sizeof($products)-1)
                            @if(date('d',strtotime($products[$iterador]->date)) != date('d',strtotime($products[$iterador+1]->date)))
                                @if($totaldia != 0)
                                <tr>
                                    <td colspan="12">Venta total del día ${{$totaldia}}</td>
                                </tr>
                                    @php
                                        $totaldia = 0;
                                    @endphp
                                @endif
                            @endif
                        @endif
                        
                        @if($iterador == sizeof($products)-1)
                            @if($totaldia != 0)
                                <tr>
                                    <td colspan="12">Venta final del día ${{$totaldia}}</td>
                                </tr>
                            @endif
                            
                            <tr>
                                <td colspan="12">Venta total del mes ${{$totalmes}}</td>
                            </tr>
                            @php
                                $totalmes = 0;
                            @endphp
                            
                            @php
                                $totaldia = 0;
                            @endphp
                        @endif

                        @if($iterador < sizeof($products)-1)
                            @if(date('m',strtotime($products[$iterador]->date)) != date('m',strtotime($products[$iterador+1]->date)))
                                @if($totalmes != 0)
                                <tr>
                                    <td colspan="12">Venta total del mes ${{$totalmes}}</td>
                                </tr>
                                
                                    @php
                                        $totalmes = 0;
                                    @endphp    
                                @endif
                            @endif
                        @endif

                    @endif


                    @if ($b->id == $p->branch_office_id )
                    <tr>
                        @php
                            $totalVendido +=$p->quantity;
                            $totaldia += $p->total;
                            $totalmes += $p->total;
                        @endphp
                        <td>{{$p->product_name}}</td>
                        <td>{{$p->category}}</td>
                        @if ($p->brand == null)
                        <td>N/A</td>
                        @else
                        <td>{{$p->brand }}</td>
                        @endif

                        <td>{{$p->quantity}}</td>
                        @if (Auth::user()->rol_id == 1 )
                        <td>${{$p->cost}}</td>
                        @endif
                        <td>${{$p->sale_price}}</td>
                        <td>${{$p->amount_discount * $p->quantity}}</td>
                        @if (Auth::user()->rol_id == 1 )
                        <td>${{$p->cost * $p->quantity}}</td>
                        @endif
                        <td>${{$p->total}}</td> 
                        <td>{{$p->seller.' '.$p->seller_lastName}}</td> 
                        <td>{{date('Y-m-d',strtotime($p->date))}}</td> 
                        <td>{{date('H:i:s',strtotime($p->date))}}</td> 
                    </tr>
                    
                        @if($iterador < sizeof($products)-1)
                            @if(date('d',strtotime($products[$iterador]->date)) != date('d',strtotime($products[$iterador+1]->date)))
                            <tr>
                                <td colspan="12">Venta total del día ${{$totaldia}}</td>
                            </tr>
                                @php
                                    $totaldia = 0;
                                @endphp    
                            @endif
                        @endif
                        @if($iterador == sizeof($products)-1)
                            <tr>
                                <td colspan="12">Venta final del día ${{$totaldia}}</td>
                            </tr>
                            <tr>
                                <td colspan="12">Venta total del mes ${{$totalmes}}</td>
                            </tr>
                        @endif
                        @if($iterador < sizeof($products)-1)
                            @if(date('m',strtotime($products[$iterador]->date)) != date('m',strtotime($products[$iterador+1]->date)))
                            <tr>
                                <td colspan="12">Venta total del mes ${{$totalmes}}</td>
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
                    <td colspan="10" class="backgroundColor">
                        RESUMEN GENERAL
                    </td>
                </tr>
                @else
                
                <tr>
                    <th colspan="8" class="backgroundColor">
                        RESUMEN GENERAL
                    </th>
                </tr>
                
                    
                @endif
                <tr>
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

                @foreach ($ap as $p)
                <tr>
                    
                    <td>{{$p->product_name}}</td>
                    <td>{{$p->category}}</td>
                    @if ($p->brand == null)
                    <td>N/A</td>
                    @else
                    <td>{{$p->brand }}</td>
                    @endif

                    <td>{{$p->quantity}}</td>
                    @if (Auth::user()->rol_id == 1 )
                    <td>${{$p->cost}}</td>
                    @endif
                    <td>${{$p->sale_price}}</td>
                    <td>${{$p->amount_discount * $p->quantity}}</td>
                    @if (Auth::user()->rol_id == 1 )
                    <td>${{$p->cost * $p->quantity}}</td>
                    @endif
                    <td>${{$p->total}}</td> 
                    <td>{{$p->seller.' '.$p->seller_lastName}}</td> 
                    {{-- <td>{{date('Y-m-d',strtotime($p->date))}}</td> 
                    <td>{{date('H:m:s',strtotime($p->date))}}</td>  --}}


                </tr>            
                @endforeach
            </table>




            <table style="width: 100%; margin-top:20px;">
                
                @if (Auth::user()->rol_id == 1 )
                <tr>
                    <th>TOTAL VENTAS</th>
                    <td colspan="3">${{number_format($cash->subtotal + $card->subtotal,2)}}</td>
                    {{-- <th>INVERSIÓN</th>
                    <td>${{$cash->costo + $card->costo}}</td> --}}

                </tr>
                @else
                <tr>
                    <th>TOTAL VENTAS</th>
                    <td colspan="3">${{number_format($cash->subtotal + $card->subtotal,2)}}</td>
                </tr>
                @endif

                <tr>
                    <th>DINERO EFECTIVO</th>
                    <td colspan="3">${{number_format($cash->total,2)}}</td>
                </tr>
                <tr>
                    <th>DINERO ELECTRÓNICO</th>
                    <td colspan="3">${{number_format($card->total,2)}}</td>
                </tr>
                <tr>
                    {{-- <th>GANANCIA</th>
                    <td>${{($cash->subtotal + $card->subtotal) - ($cash->costo + $card->costo) }}</td> --}}
                    <th>DESCUENTOS</th>
                    <td colspan="3">${{$cash->descuento + $card->descuento}}</td>
                    <th>TOTAL DE PRODUCTOS VENDIDOS</th>
                    <td colspan="3">{{$totalVendido}}</td>
                </tr>
                {{-- <tr>
                    <th>GASTOS</th>
                    <td colspan="3">${{$card->expense}}</td>
                </tr> --}}
            </table>

            <h5 style="margin: 20px;">REPORTE GENERADO POR {{strtoupper($user->name)}}</h5>
            <h5 style="margin: 5px;">{{$date}}</h5>
            
        </div>
    </body>
</html>