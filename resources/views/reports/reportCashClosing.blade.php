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
            @foreach ($branchOffice as $b)
            <table style="width: 100%; margin-top:20px;">
                @if (Auth::user()->rol_id == 1)
                <tr>
                    <th colspan="9" class="backgroundColor">
                        SUCURSAL
                    </th>
                </tr>
                <tr>
                    <td colspan="9">
                        {{$b->name}}
                    </td>
                </tr>
                @else
                <tr>
                    <th colspan="7" class="backgroundColor">
                        SUCURSAL
                    </th>
                </tr>
                <tr>
                    <td colspan="7">
                        {{$b->name}}
                    </td>
                </tr>
                @endif
                <tr>
                    <th style="font-size: 14px" class="backgroundColor">PRODUCTO</th>
                    <th style="font-size: 14px" class="backgroundColor">CATEGORÍA</th>
                    <th style="font-size: 14px" class="backgroundColor">MARCA</th>
                    <th style="font-size: 14px" class="backgroundColor">CANTIDAD</th>
                    @if (Auth::user()->rol_id == 1 )
                    <th style="font-size: 14px" class="backgroundColor">COSTO</th>  
                    @endif
                    <th style="font-size: 14px" class="backgroundColor">PRECIO <br/> PÚBLICO</th>
                    <th style="font-size: 14px" class="backgroundColor">DESCUENTO</th>
                    @if (Auth::user()->rol_id == 1 )
                    <th style="font-size: 14px" class="backgroundColor">INVERSION</th>  
                    @endif
                    <th style="font-size: 14px" class="backgroundColor">TOTAL</th>
                </tr>
                @foreach ($products as $p)
                @if ($b->id == $p->sale->branch_office_id )
                <tr>
                    
                    <td>{{$p->product->name}}</td>
                    <td>{{$p->product->category->name}}</td>
                    @if ($p->product->brand == null)
                    <td>N/A</td>
                    @else
                    <td>{{$p->product->brand->name }}</td>
                    @endif
                    <td>{{$p->quantity}}</td>
                    @if (Auth::user()->rol_id == 1 )
                    <td>${{$p->product->cost}}</td>
                    @endif
                    <td>${{$p->sale_price}}</td>
                    <td>${{($p->sale_price * (($p->PD/100)  ) ) * $p->quantity}}</td>
                    @if (Auth::user()->rol_id == 1 )
                    <td>${{$p->product->cost * $p->quantity}}</td>
                    @endif
                    <td>${{$p->total}}</td> 
                    

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
                    <td>${{$card->total }}</td>
                    <th>DESCUENTOS</th>
                    <td>${{$cash->descuento + $card->descuento}}</td>
                    
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
            </table> --}}

            <h5 style="margin: 20px;">REPORTE GENERADO POR {{strtoupper($user->name)}}</h5>
            <h5 style="margin: 5px;">{{$date}}</h5>
            
        </div>
    </body>
</html>