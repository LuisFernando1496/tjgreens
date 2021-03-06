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
      @php
          $cantidadProduct = 0;
      @endphp
        
            <h4 >REPORTE DE INVENTARIO</h4>
            <table style="width: 100%; margin-top:20px;">
                @if (Auth::user()->rol_id == 1)
                    <tr>
                        <th colspan="8" class="backgroundColor">
                            SUCURSAL
                        </th>
                    </tr>
                    <tr>
                        <td colspan="8">
                            {{$branchOffice->name}}
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
                            {{$branchOffice->name}}
                        </td>
                    </tr>
                @endif
                <tr>
                    <th class="backgroundColor">PRODUCTO</th>
                    <th class="backgroundColor">CATEGORÍA</th>
                    <th class="backgroundColor">MARCA</th>
                    <th class="backgroundColor">CANTIDAD</th>
                    <th class="backgroundColor" colspan="3">PRECIOS (PRODUCTO 1, 2 y 3)</th>
                    <!--<th class="backgroundColor">PRECIO 2</th>
                    <th class="backgroundColor">PRECIO 3</th>-->
                    @if (Auth::user()->rol_id == 1)
                    <th class="backgroundColor">COSTO</th>
                    @endif
                </tr>
                @foreach ($products as $p)
                @if ($branchOffice->id == $p->branch_office_id )
                        @php
                        $cantidadProduct ++;
                    @endphp
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
                    <td>{{$p->price_1}}</td>
                    @if($p->price_2 == 0)
                    <td >N/A</td>
                    @else
                    <td >{{$p->price_2}}</td>
                    @endif
                    @if($p->price_3 == 0)
                    <td >N/A</td>
                    @else
                    <td >{{$p->price_3}}</td>
                    @endif
                    @if (Auth::user()->rol_id == 1)
                    <td>{{$p->cost}}</td>
                    @endif
                    

                </tr>
                @endif
                @endforeach
            </table>


            <h5 style="margin: 5px;">Total de productos: {{$cantidadProduct}}</h5>
            <h5 style="margin: 20px;">REPORTE GENERADO POR {{strtoupper($user->name)}}</h5>
            <h5 style="margin: 5px;">{{$date}}</h5>
            
    
    </body>
</html>