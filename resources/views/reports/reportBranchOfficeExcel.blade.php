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
                        <h4 style="padding-right: 15em">REPORTE DE VENTAS</h4>
                    </th>
                </tr>

            </table> --}}
            <h4 >REPORTE DE VENTAS</h4>
            <h5>DESDE {{$from}} HASTA {{$to}}</h5>

            @foreach ($branchOffice as $b)
            @if (Auth::user()->rol_id == 1)
            <table style="width: 100%; margin-top:20px;" cellpadding="15">
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
            <table style="width: 100%; margin-top:20px;"  cellpadding="15">
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
                    <th colspan="2" style="font-size: 10px" class="backgroundColor"> <b>PRODUCTO</b> </th>
                    <th style="font-size: 10px" class="backgroundColor"></th>
                    <th style="font-size: 10px" class="backgroundColor"> <b>CATEGORÍA</b> </th>
                    <th style="font-size: 10px" class="backgroundColor"> <b>MARCA</b> </th>
                    <th style="font-size: 10px" class="backgroundColor"> <b>CANTIDAD</b> </th>
                    @if (Auth::user()->rol_id == 1 )
                    <th style="font-size: 10px" class="backgroundColor"> <b>COSTO</b> </th>  
                    @endif
                    <th style="font-size: 10px" class="backgroundColor"><b> PRECIO <br/> PÚBLICO</b> </th>
                    <th style="font-size: 10px" class="backgroundColor"><b> DESCUENTO</b> </th>

                    @if (Auth::user()->rol_id == 1 )
                    <th style="font-size: 10px" class="backgroundColor"> <b>INVERSION</b> </th>  
                    @endif
                    <th style="font-size: 10px" class="backgroundColor"> <b>TOTAL</b> </th>
                </tr>
                @foreach ($products as $p)
                @if ($b->id == $p->sale->branch_office_id )
                <tr>
                    
                    <td colspan="2">{{$p->product->name}}</td>
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
                    <td>${{$p->amount_discount * $p->quantity}}</td>
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
                @if (Auth::user()->rol_id == 1 )
                <tr>
                    <th>TOTAL VENTAS</th>
                    <td>${{$cash->subtotal + $card->subtotal}}</td>
                    <th>INVERSIÓN</th>
                    <td>${{$cash->costo + $card->costo}}</td>
                </tr>
                @else
                <tr>
                    <th>TOTAL VENTAS</th>
                    <td colspan="3">${{$cash->subtotal + $card->subtotal}}</td>
                </tr>
                @endif
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