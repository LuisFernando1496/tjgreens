<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orden de Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Orden de Compra</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $total = 0;
                    @endphp
                    <div class="container">
                        <h5>Compras nuevas</h5>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Precio Unitario</th>
                                    <th>Cantidad</th>
                                    <th>Costo</th>
                                    <th>Marca</th>
                                    <th>Categoria</th>
                                    <th>Total Inversión</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($compras as $compra)
                                    <tr>
                                        <td>{{$compra->id}}</td>
                                        <td>{{$compra->name}}</td>
                                        <td>${{$compra->price}}</td>
                                        <td>{{$compra->stock}}</td>
                                        <td>${{$compra->cost}}</td>
                                        @php
                                            $t = $compra->stock * $compra->cost;
                                            $total += $t
                                        @endphp
                                        <td>{{$compra->marca->name}}</td>
                                        <td>{{$compra->categoria->name}}</td>
                                        <td>${{number_format($t,2,'.',',')}}</td>
                                    </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="container">
                        <h5>Compras abastecimiento</h5>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Precio Unitario</th>
                                    <th>Cantidad</th>
                                    <th>Costo</th>
                                    <th>Marca</th>
                                    <th>Categoria</th>
                                    <th>Total Inversión</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recompras as $compra)
                                    <tr>
                                        <td>{{$compra->id}}</td>
                                        <td>{{$compra->inventario->name}}</td>
                                        <td>${{$compra->inventario->price}}</td>
                                        <td>{{$compra->quantity}}</td>
                                        <td>${{$compra->inventario->cost}}</td>
                                        <td>{{$compra->inventario->marca->name}}</td>
                                        <td>{{$compra->inventario->categoria->name}}</td>
                                        <td>{{$compra->total}}</td>
                                        @php
                                            $total += $compra->total;
                                        @endphp
                                    </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="container">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Total Invertido:</th>
                                    <th>${{number_format($total,2,'.',',')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
