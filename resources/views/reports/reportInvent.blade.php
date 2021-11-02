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
                        <h4 style="padding-right: 15em">REPORTE DE INVENTARIO</h4>
                    </th>
                </tr>

            </table> --}}
        
            <h4 >REPORTE DE INVENTARIO</h4>
            @foreach ($branchOffice as $b)
            <table style="width: 100%; margin-top:20px;">
                @if (Auth::user()->rol_id == 1)
                <tr>
                    <th colspan="5" class="backgroundColor">
                        SUCURSAL
                    </th>
                </tr>
                <tr>
                    <td colspan="5">
                        {{$b->name}}
                    </td>
                </tr>
                @else
                <tr>
                    <th colspan="4" class="backgroundColor">
                        SUCURSAL
                    </th>
                </tr>
                <tr>
                    <td colspan="4">
                        {{$b->name}}
                    </td>
                </tr>
                @endif
                <tr>
                    <th class="backgroundColor">PRODUCTO</th>
                    <th class="backgroundColor">CATEGORÍA</th>
                    <th class="backgroundColor">MARCA</th>
                    <th class="backgroundColor">CANTIDAD</th>
                    @if (Auth::user()->rol_id == 1)
                    <th class="backgroundColor">COSTO</th>
                    @endif
                </tr>
                @foreach ($products as $p)
                @if ($b->id == $p->branch_office_id )
                <tr>
                    
                    <td>{{$p->name}}</td>
                    <td>{{$p->category->name}}</td>

                    @if ($p->brand == null)
                    <td>N/A</td>
                    @else
                    <td>{{$p->brand->name }}</td>
                    @endif

                    @if ($p->stock == null)
                    <td>N/A</td>
                    @else
                    <td>{{$p->stock}}</td> 
                    @endif
                    @if (Auth::user()->rol_id == 1)
                    <td>${{$p->cost}}</td>
                    @endif
                    
                    

                </tr>
                @endif
                @endforeach
            </table>
            @endforeach


            {{-- <table style="width: 100%; margin-top:20px;">
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