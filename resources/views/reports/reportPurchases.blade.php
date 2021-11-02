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
@php
    $totalUsd = 0;
    $totalMxn = 0;
@endphp
            <h4 >REPORTE DE COMPRAS</h4>
            <h5>DESDE {{$from}}-- HASTA {{$to}}</h5>
          
            <table style="width: 100%; margin-top:20px;">
              
                    
                <tr>
                    <th colspan="7" class="backgroundColor">
                   {{$compras[0]->name_office}}
                    </th>
                </tr>
                <tr>
                    <td colspan="7">
                      Productos
                    </td>
                </tr>
             
                    

                <tr>
                    <th scope="col"  class="backgroundColor">Nombre</th>
                    <th scope="col"  class="backgroundColor">Categoria</th>
                    <th scope="col"  class="backgroundColor">Costo pza (MXN)</th>
                    <th scope="col"  class="backgroundColor">Costo pza (USD)</th>
                    <th scope="col"  class="backgroundColor">Cantidad</th>
                    <th scope="col"  class="backgroundColor">Total (MXN)</th>
                    <th scope="col"  class="backgroundColor">Total (USD)</th>
                   
                </tr>
               @foreach ($compras as $compra)
              
                <tr>
                    
                    <th scope="row">{{$compra->product->name}}</th>
                  <th scope="row">{{$compra->product->category->name}}</th>
                    <th scope="row">${{$compra->product->cost}}</th>
                    <th scope="row">${{number_format(($compra->product->cost /20.68),2)}}</th>
                    <th scope="row">{{$compra->quantity}}</th>
                    <th scope="row">${{$compra->total}} </th>
                    <th scope="row">${{number_format(($compra->total/20.68),2)}} </th>

                </tr>
           @php
               $totalUsd += ($compra->total/20.68);
               $totalMxn += $compra->total;
           @endphp
                @endforeach
            </table>

       
            <table style="width: 100%; margin-top:20px;">
                
          
                <tr>
                    <th>TOTAL COMPRAS (MXN)</th>
                    <td colspan="3">${{$totalMxn}}</td>
                    {{-- <th>INVERSIÓN</th>
                    <td>${{$cash->costo + $card->costo}}</td> --}}

                </tr>
              
                <tr>
                    <th>TOTAL COMPRAS (USD)</th>
                    <td colspan="3">${{number_format($totalUsd,2)}}</td>
                </tr>
              

             
            </table>


        
            
        </div>
    </body>
</html>