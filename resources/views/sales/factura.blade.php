<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <center><title>Factura de venta</title></center>
</head>
<body>

<style>
    .backgroundColor{
        background: red;
    }
    .centrado {
        text-align: center;
        align-content: center;
        width: 100%;
    }
</style>
    <div class="ticket">
        <center>
            <img src="{{asset('/logo_inusual.png')}}" alt="Logotipo">
            <p class="centrado">
                Calle {{$sale->branchOffice->address->street}},Numero {{$sale->branchOffice->address->ext_number}} <br>
                Colonia {{$sale->branchOffice->address->suburb}} <br>
                Atendido por {{Auth::user()->name}} {{Auth::user()->last_name}} <br>
                Fecha: {{$sale->created_at->format('d-m-y h:m:s')}} <br>
                Folio: {{$sale->id}}<br>
            </p>
        </center>
        <table style="width: 100%; margin-top:20px;" class="display table table-striped table-bordered">
            <thead style="font-size: 90%">
                <tr>
                    <th colspan="8">SUCURSAL {{$sale->branchOffice->name}}</th>
                </tr>
                <tr>
                    <th>PRODUCTO</th>
                    <th>CATEGORIA</th>
                    <th>MARCA</th>
                    <th>CANTIDAD</th>
                    <th>P/U</th>
                    <th>VENDEDOR</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody style="font-size: 70%; font-weight: normal;">
                @foreach($sale->productsInSale as $key => $product)
                    <tr>
                        <th>{{$product->product->name}}</th>
                        <th>{{$sales[$key]->category}}</th>
                        <th>{{$sales[$key]->brand}}</th>
                        <th>{{$product->quantity}}</th>
                        <th>${{$product->product->price_1}}</th>
                        <th>{{$sales[$key]->name.' '.$sales[$key]->last_name}}</th>
                        <th>${{$product->quantity * $product->product->price_1}}</th>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br><br>
        <table style="width: 100%; margin-top:20px;" class="display table table-striped table-bordered">
            <thead style="font-size: 85%">
                <tr>
                    <th>TIPO DE PAGO</th>
                    <th>SUBTOTAL</th>
                    <th>DESCUENTO</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody style="font-size: 69%">
                @if ($sale->payment_type == 0)
                <th>Pago en efectivo</th>
                @elseif($sale->payment_type == 1)
                <th>Pago con tarjeta</th>
                @elseif($sale->payment_type == 3)
                <th>Pago mixto</th>
                @else
                <th>Pago a crédito</th>
                @endif
                <th>${{number_format($sale->cart_subtotal,2,'.',',')}}</th>
                <th>${{number_format($sale->amount_discount,2,'.',',')}}</th>
                <th>${{number_format($sale->cart_total,2,'.',',')}}</th>
            </tbody>
        </table>
        <br><br><br>
        <p class="centrado">___________________________________</p>
        <p class="centrado">Nombre y firma</p>
        {{--<section id="ticket" style="display: flex; justify-content: space-between; align-items: center;">
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
            @elseif($sale->payment_type == 3)
            Pago mixto
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
        </div>
        <br>
        <div class="centrado">
            <img  src="{{asset('/qr-tj.svg')}}" style="width: 100px; height:100px" alt="Logotipo">
        </div>
   
        <p class="centrado" >¡GRACIAS POR SU COMPRA!</p>
        <p class="centrado">Este ticket no es comprobante fiscal y se incluirá en la venta del día</p>--}}
    </div>
</body>
<script>
    window.print();
    window.addEventListener("afterprint", function(event) {
        window.close()
    });
</script>
</html>