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
        <div style="text-align:center; margin-left: auto; margin-right: auto;">
            {{-- <table style="width: 100%; margin-top:20px;">
                <tr>
                    <th colspan="1" style=" border-color: transparent" >
                        <img  src="{{ public_path('logopdf.png') }}" width="150px;">
                    </th>
                    <th colspan="4" style=" border-color: transparent" >
                        <h4 style="padding-right: 15em">REPORTE DE CORTE DE CAJA</h4>
                    </th>
                </tr>

            </table> --}}
            <h4 >REPORTE DE CORTE DE CAJA</h4>
            <h4>Caja atendida por:  <strong>{{$worker->name}}</strong></h4>
            @foreach ($branchOffice as $b)
            <table style="width: 100%; margin-top:20px;">
                @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
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
                @else
                <tr>
                    <th colspan="8" class="backgroundColor">
                        SUCURSAL
                    </th>
                </tr>
                <tr>
                    <td colspan="8">
                        {{$b->name}}
                    </td>
                </tr>
                @endif
                <tr>
                    <th style="font-size: 14px" class="backgroundColor">PRODUCTO</th>
                    <th style="font-size: 14px" class="backgroundColor">CATEGORÍA</th>
                    <th style="font-size: 14px" class="backgroundColor">MARCA</th>
                    <th style="font-size: 14px" class="backgroundColor">CANTIDAD</th>
                    <th style="font-size: 14px" class="backgroundColor">TIPO DE PAGO</th>  
                    @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                    <th style="font-size: 14px" class="backgroundColor">COSTO</th>  
                   

                    @endif
                    <th style="font-size: 14px" class="backgroundColor">PRECIO <br/> PÚBLICO</th>
                    <th style="font-size: 14px" class="backgroundColor">DESCUENTO</th>
                    @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                    <th style="font-size: 14px" class="backgroundColor">INVERSION</th>  
                    @endif
                    <th style="font-size: 14px" class="backgroundColor">TOTAL</th>
                </tr>
                @php

                $tipo=0;
                $efectivoVenta = 0;
                $electronicoVenta = 0;
                
                @endphp
                @foreach ($products as $p)
                @if ($b->id == $p->sale->branch_office_id )
                @php
                
                if($p->payment_type == 0){
                    $efectivoVenta += $p->total;
                    $tipo = 'Efectivo';
                }
               if($p->payment_type == 1){
                $tipo = 'Electronico';
                $electronicoVenta += $p->total;
               }
               @endphp
                <tr>
                    
                    <td>{{$p->product->name}}</td>
                    <td>{{$p->product->category->name}}</td>
                    @if ($p->product->brand == null)
                    <td>N/A</td>
                    @else
                    <td>{{$p->product->brand->name }}</td>
                    @endif
                    <td>{{$p->quantity}}</td>
                    <td>{{$tipo}}</td>
                    @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                    <td>{{$p->product->cost}}</td>
                    
                    @endif
                    <td>{{$p->sale_price}}</td>
                    <td>{{($p->sale_price * (($p->PD/100)  ) ) * $p->quantity}}</td>
                    @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                    <td>{{$p->product->cost * $p->quantity}}</td>
                    @endif
                    <td>{{$p->total}}</td> 
                    

                </tr>
                @endif
                @endforeach
            </table>
            @endforeach


            <table style="width: 100%; margin-top:20px;">
             <!--   <tr>
                    <th>CAJA INICIAL</th>
                    <td>${{$cash->caja_inicial}}</td>
                    <th>CAJA FINAL</th>
                    <td>${{ number_format($cash->caja_inicial + $cash->total, 2, '.', '')}}</td>
                </tr>
                -->
                <tr>
                    <th>TOTAL VENTAS</th>
                    <td>{{number_format($cash->subtotal + $card->subtotal,2)}}</td>
                    <th>DINERO EFECTIVO</th>
                    <td>{{number_format($efectivoVenta,2)}}</td>
                    <!--
                    <th>INVERSIÓN</th>
                    <td>${{$cash->costo + $card->costo}}</td>
                    -->
                </tr>
                <tr>
                    
                    <th>DINERO ELECTRÓNICO</th>
                    <td>{{number_format($electronicoVenta,2) }}</td>
                    <th>DESCUENTOS</th>
                    <td>{{$cash->descuento + $card->descuento}}</td>
                    
                </tr>
                <!--<tr>
                    <th>GANANCIA</th>
                    <td>${{($cash->subtotal + $card->subtotal) - ($cash->costo + $card->costo) }}</td>

                </tr>
                <tr>
                    <th>GASTOS</th>
                    <td colspan="3">${{$card->expense}}</td>
                </tr>-->
            </table>
            <!--  
            {{-- <table style="width: 100%; margin-top:20px;">
                <tr>
                    <th>CAJA INICIAL</th>
                    <td>${{$cash->caja_inicial}}</td>
                    <th>CAJA FINAL</th>
                    <td>${{$cash->caja_final}}</td>
                </tr>
                -->
                <tr>
                    <th>TOTAL VENTAS</th>
                    <td>${{$cash->subtotal + $card->subtotal}}</td>
                    <th>DINERO EFECTIVO</th>
                    <td>${{$cash->total}}</td>

                    <!--
                    <th>INVERSIÓN</th>
                    <td>${{$cash->costo + $card->costo}}</td>
                    -->
                </tr>
                <tr>
                    
                    <th>DINERO ELECTRÓNICO</th>
                    <td>${{$card->total}}</td>
                    <th>DESCUENTOS</th>
                    <td>${{$cash->descuento + $card->descuento}}</td>
                    
                </tr>
                <!--
                <tr>
                    <th>GANANCIA</th>
                    <td>${{($cash->subtotal + $card->subtotal) - ($cash->costo + $card->costo) }}</td>
                    
                    
                </tr>
                <tr>
                    <th>GASTOS</th>
                    <td colspan="3">${{$card->expense}}</td>
                </tr>
                -->
            </table> 

            <h5 style="margin: 20px;">REPORTE GENERADO POR {{strtoupper($user->name)}}</h5>
            <h5 style="margin: 5px;">{{$date}}</h5>--}} -->
            
        </div>
    </body>
</html>