@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title">Ventas de almacén</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Sucursal</th>
                                <th>Tipo Pago</th>
                                <th>Sub Total</th>
                                <th>Descuento %</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ventas as $venta)
                                <tr>
                                    <td>{{$venta->id}}</td>
                                    <td>{{$venta->oficina->name}}</td>
                                    <td>{{$venta->type}}</td>
                                    <td>${{$venta->subtotal}}</td>
                                    <td>{{$venta->discount}}%</td>
                                    <td>${{$venta->total}}</td>
                                    <td>{{$venta->status}}</td>
                                    <td>
                                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#ventModal{{$venta->id}}"><i class="bi bi-eye-fill"></i></button>
                                        @if ($venta->status == "En proceso")
                                            <form action="{{route('venta.pagada',$venta->id)}}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-outline-success" type="submit"><i class="bi bi-cash"></i></button>
                                            </form>
                                        @else
                                            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-receipt"></i></button>
                                        @endif
                                    </td>
                                </tr>

                            @empty

                            @endforelse
                        </tbody>
                    </table>

                    @forelse ($ventas as $venta)
                        <div class="modal fade" id="ventModal{{$venta->id}}" tabindex="-1" aria-labelledby="addInventario{{$venta->id}}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Productos de Venta</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Producto</th>
                                                        <th>Precio</th>
                                                        <th>Cantidad</th>
                                                        <th>Subtotal</th>
                                                        <th>Descuento %</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($venta->productos as $product)
                                                        <tr>
                                                            <td>{{$product->id}}</td>
                                                            <td>{{$product->inventario->name}}</td>
                                                            <td>${{$product->inventario->price}}</td>
                                                            <td>{{$product->quantity}}</td>
                                                            @php
                                                                $subtotal = ($product->quantity * $product->inventario->price);
                                                            @endphp
                                                            <td>${{number_format($subtotal,2,'.',',')}}</td>
                                                            <td>{{$product->discount}}%</td>
                                                            <td>${{$product->total}}</td>
                                                        </tr>
                                                    @empty

                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
