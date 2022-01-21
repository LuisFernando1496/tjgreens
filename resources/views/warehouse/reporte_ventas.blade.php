<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    @php
        $subtotalGeneral = 0;
        $descuentoGeneral = 0;
        $totalGeneral = 0;
    @endphp
    <br>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" style="text-align: center">Reporte de Ventas de {{$fecha}}</h4>
            </div>
            <div class="card-body">
                <h6 class="card-title">Todas las ventas</h6>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <td>Sucursal</td>
                            <td>Método Pago</td>
                            <td>Status</td>
                            <td>Subtotal</td>
                            <td>Descuento%</td>
                            <td>Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reportes as $reporte)
                            <tr>
                                <td>{{$reporte->id}}</td>
                                <td>{{$reporte->oficina->name}}</td>
                                <td>{{$reporte->type}}</td>
                                <td>{{$reporte->status}}</td>
                                <td>${{$reporte->subtotal}}</td>
                                <td>{{$reporte->discount}}%</td>
                                <td>${{$reporte->total}}</td>
                            </tr>
                        @empty

                        @endforelse
                    </tbody>
                </table>
                <br>
                <hr>
                <h6 class="card-title">Ventas Desglozadas</h6>
                @forelse ($reportes as $reporte)
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title" style="text-align: center">Folio</h6>
                                </div>
                                <div class="card-body">
                                    <b>{{$reporte->id}}</b>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title" style="text-align: center">Sucursal</h6>
                                </div>
                                <div class="card-body">
                                    <b>{{$reporte->oficina->name}}</b>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title" style="text-align: center">Método Pago</h6>
                                </div>
                                <div class="card-body">
                                    <b>{{$reporte->type}}</b>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title" style="text-align: center">Subtotal</h6>
                                </div>
                                <div class="card-body">
                                    <b>${{$reporte->subtotal}}</b>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title" style="text-align: center">Descuento</h6>
                                </div>
                                <div class="card-body">
                                    <b>{{$reporte->discount}}%</b>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title" style="text-align: center">Total</h6>
                                </div>
                                <div class="card-body">
                                    <b>${{$reporte->total}}</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="container">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                        <th>Descuento</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reporte->productos as $producto)

                                        <tr>
                                            <td>{{$producto->id}}</td>
                                            <td>{{$producto->inventario[0]->name}}</td>
                                            <td>${{$producto->inventario[0]->price}}</td>
                                            <td>{{$producto->quantity}}</td>
                                            @php
                                                $subtotal = $producto->quantity * $producto->inventario[0]->price;
                                                $subtotalGeneral += $subtotal;
                                                $totalGeneral += $producto->total;
                                            @endphp
                                            <td>${{number_format($subtotal,2,'.',',')}}</td>
                                            <td>{{$producto->discount}}%</td>
                                            <td>${{$producto->total}}</td>
                                        </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                @empty

                @endforelse
                <br>
                <hr>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Subtotal:</th>
                            <th>Descuento:</th>
                            <th>Total:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${{number_format($subtotalGeneral,2,'.',',')}}</td>
                            @php
                                $descuentoGeneral = $subtotalGeneral - $totalGeneral;
                            @endphp
                            <td>${{number_format($descuentoGeneral,2,'.',',')}}</td>
                            <td>${{number_format($totalGeneral,2,'.',',')}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        window.print();
        window.addEventListener("afterprint", function(event) {
            window.close()
        });
    </script>
</body>
</html>
