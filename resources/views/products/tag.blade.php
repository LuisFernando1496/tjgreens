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
    size: 1px;
}
.centrado1 {
  
    width: 100%;
    size: 1px;
    padding-left: 20px;
}
.ticket {
    width: 150px;
    max-width: 150px;
   
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
    <p class="centrado">
        Código: {{$product->bar_code}} <br> 
    </p>
    <p class="centrado1">
        {!! DNS1D::getBarcodeHTML($product->bar_code, 'C39')!!}  <br>
        
        @if($product->price_1)
        Precio 1: {{$product->price_1}}  <br>
        @else
        Precio: {{$product->price}}  <br>
        @endif
        Precio en dolar: {{$product->cost * 20.68}}  <br>
    </p>
</div>
</body>
<script>
    window.print();
    window.addEventListener("afterprint", function(event) {
        window.close()
    });
</script>
</html>
