<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Factura de Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</head>

<body>
    <br>
    <div class="container">
        <div class="card mb-12">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="{{ asset('/logo_inusual.jpeg') }}" class="img-fluid rounded-start" alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Factura de Venta {{ $venta->id }}</h5>
                        <p class="card-text">Factura de venta realizada por {{ $venta->usuario[0]->name }}
                            {{ $venta->usuario[0]->last_name }}, en la fecha {{ $venta->created_at }}</p>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detalle de venta con N° {{$venta->id}}</h4>
            </div>
            <div class="card-body">
                <hr>
                <h5 class="card-title">Productos</h5>
                @php
                    $costo_total = 0;
                    $precio_total = 0;
                    $suma = 0;
                @endphp
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Marca</th>
                            <th>Categoria</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                            <th>Precio Unitario</th>
                            <th>Precio Total</th>
                            <th>Subtotal</th>
                            <th>Descuento (%)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($venta->productos as $inventario)
                            <tr>
                                <td>{{$inventario->inventario[0]->name}}</td>
                                <td>{{$inventario->inventario[0]->marca->name}}</td>
                                <td>{{$inventario->inventario[0]->categoria->name}}</td>
                                <td>{{$inventario->quantity}}</td>
                                <td>${{number_format($inventario->inventario[0]->cost,2,'.',',')}}</td>
                                @php
                                    $ct = $inventario->quantity * $inventario->inventario[0]->cost;
                                    $costo_total += $ct;
                                @endphp
                                <td>${{number_format($ct,2,'.',',')}}</td>
                                <td>${{number_format($inventario->inventario[0]->price,2,'.',',')}}</td>
                                @php
                                    $pt = $inventario->quantity * $inventario->inventario[0]->price;
                                    $precio_total += $pt;
                                @endphp
                                <td>${{number_format($pt,2,'.',',')}}</td>
                                @php
                                    $subtotal = $inventario->quantity * $inventario->inventario[0]->cost;
                                    $descuentot = $inventario->discount / 100;
                                    $tt = $subtotal - ($subtotal * $descuentot);
                                    $suma += $tt;
                                @endphp
                                <td>${{number_format($subtotal,2,'.',',')}}</td>
                                <td>{{$inventario->discount}}%</td>
                                <td>${{number_format($tt,2,'.',',')}}</td>
                            </tr>
                        @empty

                        @endforelse
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>Costo Total:</b></td>
                            <td><b>${{number_format($costo_total,2,'.',',')}}</b></td>
                            <td><b>Precio Total:</b></td>
                            <td><b>${{number_format($precio_total,2,'.',',')}}</b></td>
                            <td></td>
                            <td><b>Total:</b></td>
                            <td><b>${{number_format($suma,2,'.',',')}}</b></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <br>
                <br>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="text-align: center">Nombre y Firma de quien recibe</th>
                            <th style="text-align: center">Nombre y Firma de quien entrega</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
