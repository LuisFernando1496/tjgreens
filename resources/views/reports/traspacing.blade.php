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
                    <th style="font-size: 10px" class="backgroundColor">DESCRIPTION</th>
                    <th style="font-size: 10px" class="backgroundColor">QTY</th>
                    <th style="font-size: 10px" class="backgroundColor">UNIT PRICE</th>
                    <th style="font-size: 10px" class="backgroundColor">DISCOUNT</th>
                    <th style="font-size: 10px" class="backgroundColor">TOTAL PRICE</th>
                  
                </tr>
                
               @foreach ($send as $item)
                   <tr>
                       <td>{{$item->product->name}}</td>
                       <td>{{$item->quantity}}</td>
                       <td>${{$item->sale_price}}</td>
                       <td>${{$item->discount}}</td>
                       <td>${{$item->total}}</td>
                       @php
                           $totals += $item->total
                       @endphp
                    
                   </tr>
               @endforeach
             <tr>
                 <th colspan="4"></th>
                 <td>Total: ${{$totals}}</td>
             </tr>
            </table>




           
            
        </div>
    </body>
</html>