<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte {{$title}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <br>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" style="text-align: center">{{$title}}</h4>
                @if($title == "Traspaso" && $compra->office_id != 0)
                <h4>Sucursal destino {{$sucursal[0]->name}} </h4>
                @endif
                @if($title == "Traspaso" && $compra->office_id == 0)
                <h4>Cliente privado</h4>
                @endif
                @if($title == "Venta" && $compra->office_id == 0)
                <h4>Venta a Cliente Privado</h4>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <td>Producto</td>
                            <td>Marca</td>
                            <td>Categoria</td>
                            <td>Cantidad</td>
                            <td>Precio</td>
                            <td>Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $key => $p)
                            <tr>
                                <td>{{$p[0]->name}}</td>
                                <td>{{$p[0]->brand_name}}</td>
                                <td>{{$p[0]->category_name}}</td>
                                <td>{{$cantidad[$key]}}</td>
                                <td>${{$p[0]->price}}</td>
                                <td>${{$p[0]->price * $cantidad[$key]}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <hr>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Pago</th>
                            <th>Subtotal</th>
                            <th>Descuento</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$compra->type}}</td>
                            <td>${{$compra->subtotal}}</td>
                            <td>{{$compra->discount}}%</td>
                            <td>${{$compra->total}}</td>
                        </tr>
                    </tbody>
                </table>      
            </div>
        </div>
    </div>
</body>
</html>
