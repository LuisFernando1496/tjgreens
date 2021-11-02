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
                        <h4 style="padding-right: 15em">REPORTE DE VENTAS POR USUARIO</h4>
                    </th>
                </tr>

            </table> --}}
            <h4 >REPORTE DE VENTAS POR USUARIO</h4>
            <h5>DESDE {{$from}} HASTA {{$to}}</h5>


            @foreach ($branchOffice as $b)
            <table style="width: 100%; margin-top:20px;">


                @if (Auth::user()->rol_id == 1)
                <tr>
                    <th colspan="6" class="backgroundColor">
                        SUCURSAL
                    </th>
                    <th colspan="6" class="backgroundColor">
                        EMPLEADO
                    </th>
                </tr>
                <tr>
                    <td colspan="6">
                        {{$b->name}}
                    </td>
                    <td colspan="6">
                        {{$worker->name}}
                    </td>
                </tr>
                @else

                <tr>
                    <th colspan="5" class="backgroundColor">
                        SUCURSAL
                    </th>
                    <th colspan="5" class="backgroundColor">
                        EMPLEADO
                    </th>
                </tr>
                <tr>
                    <td colspan="5">
                        {{$b->name}}
                    </td>
                    <td colspan="5">
                        {{$worker->name}}
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
                @foreach ($products as $p)
                @if ($b->id == $p->branch_office_id )
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
                    <td>{{date('Y-m-d',strtotime($p->date))}}</td> 
                    <td>{{date('H:m:s',strtotime($p->date))}}</td> 


                </tr>
                @endif
                @endforeach
            </table>
            @endforeach


            <table style="width: 100%; margin-top:20px;">
                
                @if (Auth::user()->rol_id == 1 )
                <tr>
                    <th>TOTAL VENTAS</th>
                    <td colspan="3">${{$cash->subtotal + $card->subtotal}}</td>
                    {{-- <th>INVERSIÓN</th>
                    <td>${{$cash->costo + $card->costo}}</td> --}}

                </tr>
                @else
                <tr>
                    <th>TOTAL VENTAS</th>
                    <td colspan="3">${{$cash->subtotal + $card->subtotal}}</td>
                </tr>
                @endif

                <tr>
                    <th>DINERO EFECTIVO</th>
                    <td colspan="3">${{$cash->total}}</td>
                </tr>
                <tr>
                    <th>DINERO ELECTRÓNICO</th>
                    <td colspan="3">${{$card->total}}</td>
                </tr>
                <tr>
                    {{-- <th>GANANCIA</th>
                    <td>${{($cash->subtotal + $card->subtotal) - ($cash->costo + $card->costo) }}</td> --}}
                    <th>DESCUENTOS</th>
                    <td colspan="3">${{$cash->descuento + $card->descuento}}</td>
                </tr>
                {{-- <tr>
                    <th>GASTOS</th>
                    <td colspan="3">${{$card->expense}}</td>
                </tr> --}}
            </table>

            {{-- <table style="width: 100%; margin-top:20px;">
                <tr>
                    <th>CAJA INICIAL</th>
                    <td>${{$cash->caja_inicial}}</td>
                    <th>CAJA FINAL</th>
                    <td>${{$cash->caja_final}}</td>
                </tr>
                <tr>
                    <th>TOTAL VENTAS</th>
                    <td>${{$cash->subtotal + $card->subtotal}}</td>
                    <th>INVERSIÓN</th>
                    <td>${{$cash->costo + $card->costo}}</td>
                </tr>
                <tr>
                    <th>DINERO EFECTIVO</th>
                    <td>${{$cash->total}}</td>
                    <th>DINERO ELECTRÓNICO</th>
                    <td>${{$card->total}}</td>
                    
                </tr>
                <tr>
                    <th>GANANCIA</th>
                    <td>${{($cash->subtotal + $card->subtotal) - ($cash->costo + $card->costo) }}</td>
                    <th>DESCUENTOS</th>
                    <td>${{$cash->descuento + $card->descuento}}</td>
                </tr>
                <tr>
                    <th>GASTOS</th>
                    <td colspan="3">${{$card->expense}}</td>
                </tr>
            </table> --}}

            <h5 style="margin: 20px;">REPORTE GENERADO POR {{strtoupper($user->name)}}</h5>
            <h5 style="margin: 5px;">{{$date}}</h5>
            
        </div>
    </body>
</html>