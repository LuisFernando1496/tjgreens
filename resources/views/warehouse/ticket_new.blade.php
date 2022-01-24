<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket</title>    
</head>
<body>

<style>
    * {
    font-size: 12px;
    font-family: 'Times New Roman';
}
td,th,tr,table {
    border-top: 1px solid black;
    border-collapse: collapse;
}
td.producto,th.producto {
    width: 150px;
    max-width: 150px;
}
td.cantidad,th.cantidad {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}
td.precio,th.precio {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}
.centrado {
    text-align: center;
    align-content: center;
    width: 100%;
}
.ticket {
    width: 155px;
    max-width: 155px;
}
img {
    max-width: inherit;
    width: inherit;
}
@media print{
  .oculto-impresion, .oculto-impresion *{
    display: none !important;
  }
}

</style>
<div class="ticket">
    <img src="{{asset('/logo_inusual.png')}}" alt="Logotipo">
    <p class="centrado">
        Calle {{$sale->branchOffice->address->street}},Numero {{$sale->branchOffice->address->ext_number}} <br>
        Colonia {{$sale->branchOffice->address->suburb}} <br>
        Atendido por {{Auth::user()->name}} {{Auth::user()->last_name}} <br>
        Fecha: {{$sale->created_at->format('d-m-y h:m:s')}} <br>
        Folio: {{$sale->id}}
        {{--Sucursal {{Auth::user()->branchOffice->name}} <br>
        Calle {{Auth::user()->branchOffice->address->street}} numero {{Auth::user()->branchOffice->address->numero_exterior}},Colonia {{Auth::user()->branchOffice->address->suburb}}, <br>
        Fecha: {{$sale->created_at}} <br>
        Folio: {{$sale->id}}--}}
    </p>
    <section id="ticket" style="display: flex; justify-content: space-between; align-items: center;">
        <div id="pro-th">CANT</div>
        <div id="pre-th">PRO  <br></div>
        <div id="cod-th">P/U</div>
        <div id="subtotal">DES</div>
        <div id="subtotal">IMP</div>
    </section>
    <hr>
    @foreach($sale->productsInSale as $product)
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div id="pro-td">
                {{$product->quantity}}
            </div>
            <div id="pre-td" style="text-align: center;">{{$product->product->name}} </div>
            <div id="can-td" style="text-align: center; margin-right:3px !important;">${{number_format($product->sale_price,2,',','.')}} </div>
            <div id="can-td" style="text-align: center; margin-right:3px !important;">@if($product->discount != 0)${{number_format($product->discount,2,',','.')}}@else-@endif</div>
            <div id="subtotal" style="text-align: center;">${{number_format($product->subtotal,2,',','.')}} </div>
        </div>
        <hr>
    @endforeach
    <div id="total">
        @if ($sale->payment_type == 0)
        Pago en efectivo
        @elseif($sale->payment_type == 1)
        Pago con tarjeta
        @else
        Pago a crédito
        @endif
        =========================
        <br>
        @if($sale->discount != null)Descuento:  %{{number_format($sale->discount,2,'.',',')}}@endif
        =========================
        <br>
        Subtotal:  ${{number_format($sale->cart_subtotal,2,'.',',')}}
        =========================
        <br>
        Total: ${{number_format($sale->cart_total,2,'.',',')}}
        {{--Pago con tarjeta : $0.00 <br>
        Descuento: $0.00 <br>
        ============ <br>
        Subtotal: ${{number_format($total,2,'.',',')}}
        ============ <br>
        Total: ${{number_format($subtotal,2,'.',',')}} <br>
        ============ <br>--}}
    </div>
    <p class="centrado">RFC:{{Auth::user()->rfc}} </p>
    <p class="centrado">Email: {{Auth::user()->email}}</p>
    <p class="centrado">¡GRACIAS POR SU COMPRA!</p>
    <p class="centrado">Este ticket no es comprobante fiscal y se incluirá en la venta del día</p>
</div>
</body>
<script>
    window.print();
    window.addEventListener("afterprint", function(event) {
        window.close()
    });
</script>
</html>
