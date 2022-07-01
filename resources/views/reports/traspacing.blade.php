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
            $totals = 0;
            $counterProduct = 1;
        @endphp
<h5>TRANSFER</h5>
            <table style="width: 100%; margin-top:20px;">
        
                    
                <tr>
                    <th colspan="5" >
                      Origin {{$send[0]->trasnfer->origin->name}}
                    </th>
                </tr>
                <tr>
                    <th style="font-size: 10px"  colspan="5" >submitted to  {{$send[0]->trasnfer->branchOffice->name}} </th>
                  
                </tr>
                <tr>
                    <th style="font-size: 10px"  colspan="5" >Fecha {{$send[0]->trasnfer->created_at->format('d-m-y')}} </th>
                  
                </tr>
                <tr>
                    <th style="font-size: 10px" class="backgroundColor">#</th>
                    <th style="font-size: 10px" class="backgroundColor">MARCA</th>
                    <th style="font-size: 10px" class="backgroundColor">DESCRIPTION</th>
                    <th style="font-size: 10px" class="backgroundColor">QTY</th>
                    <th style="font-size: 10px" class="backgroundColor">UNIT COST</th>
                    <th style="font-size: 10px" class="backgroundColor">COSTO TOTAL USD</th>
                    <th style="font-size: 10px" class="backgroundColor">SELL RETAIL PRICE</th>
                    <th style="font-size: 10px" class="backgroundColor">TOTAL PRICE</th>
                  
                </tr>
                
               @foreach ($send as $item)
                   <tr>
                        <td>{{$counterProduct}}</td>
                       <td>{{$item->product->brand->name}}</td>
                       <td>{{$item->product->name}}</td>
                       <td>{{$item->quantity}}</td>
                       @if($item->cost != 0)
                            <td>{{$item->cost}}</td>
                        <td>{{$item->cost * $item->quantity}}</td>
                     
                           
                       @else
                            <td>{{$item->product->cost}}</td>
                            <td>{{$item->product->cost * $item->quantity}}</td>
                   
                       @endif
                      
                       <td>{{$item->sale_price}}</td>
                       <td>{{$item->total}}</td>
                       @php
                           $totals += $item->total;
                           $counterProduct++
                       @endphp
                    
                   </tr>
               @endforeach
             <tr>
                 <th colspan="6"></th>
                 <td>Total: {{$totals}}</td>
             </tr>
            </table>




           
            
        </div>
    </body>
</html>