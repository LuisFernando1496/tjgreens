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
                    <div class="col-2">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reportVenta"><i class="bi bi-receipt-cutoff">Reporte Ventas</i></button>
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#reporteCompra"><i class="bi bi-receipt-cutoff">Reporte Compras</i></button>
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
                                <th>Factura</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $venta)
                                <tr>
                                    <td>{{$venta->id}}</td>
                                    <td>{{$venta->oficina->name}}</td>
                                    <td>{{$venta->type}}</td>
                                    <td>${{number_format($venta->subtotal,2,'.',',')}}</td>
                                    <td>{{$venta->discount}}%</td>
                                    <td>${{number_format($venta->total,2,'.',',')}}</td>
                                    <td>{{$venta->status}}</td>
                                    <td>
                                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#ventModal{{$venta->id}}"><i class="bi bi-eye-fill"></i></button>
                                        @if ($venta->status == "En proceso")
                                            <form action="{{route('venta.pagada',$venta->id)}}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-outline-success" type="submit"><i class="bi bi-cash"></i></button>
                                            </form>
                                        @else
                                            <a href="{{route('generate.ticket',$venta->id)}}" target="blank" class="btn btn-outline-secondary" type="button"><i class="bi bi-receipt"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($venta->status != "En proceso")
                                            <a href="{{route('generate.factura',$venta->id)}}" target="blank" class="btn btn-outline-success" type="button"><i class="bi bi-receipt-cutoff"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{route('eliminar.traspaso',$venta->id)}}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                    {{$sales->links()}}

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
                                                            <td>{{$product->inventario[0]->name}}</td>
                                                            <td>${{number_format($product->inventario[0]->price,2,'.',',')}}</td>
                                                            <td>{{$product->quantity}}</td>
                                                            @php
                                                                $subtotal = ($product->quantity * $product->inventario[0]->price);
                                                            @endphp
                                                            <td>${{number_format($subtotal,2,'.',',')}}</td>
                                                            <td>{{$product->discount}}%</td>
                                                            <td>${{number_format($product->total,2,'.',',')}}</td>
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

    <div class="modal fade" id="reportVenta" tabindex="-1" aria-labelledby="reportVenta" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{route('reporte.ventas')}}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reporte de Ventas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <label for="">Fecha Inicio:</label>
                                    <input type="date" class="form-control" name="from" id="from">
                                </div>
                                <div class="col">
                                    <label for="">Fecha Final:</label>
                                    <input type="date" class="form-control" name="to" id="to">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input name="today" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                                        <label class="form-check-label" for="flexSwitchCheckChecked">¿Hoy?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Generar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="reporteCompra" tabindex="-1" aria-labelledby="reporteCompra" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{route('reporte.compras')}}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reporte de Compras</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <label for="">Fecha Inicio:</label>
                                    <input type="date" class="form-control" name="from" id="fromCompras">
                                </div>
                                <div class="col">
                                    <label for="">Fecha Final:</label>
                                    <input type="date" class="form-control" name="to" id="toCompras">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input name="today" class="form-check-input" type="checkbox" role="switch" id="switchCompras" checked>
                                        <label class="form-check-label" for="flexSwitchCheckChecked">¿Hoy?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Generar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            isCheck();
            isToday();

            $('#flexSwitchCheckChecked').click(function(){
                isCheck();
            });
            $('#switchCompras').click(function(){
                isToday();
            });

            function isCheck() {
                if ($('#flexSwitchCheckChecked').is(':checked')) {
                    $('#from').prop('readonly',true);
                    $('#to').prop('readonly',true);
                    $('#from').removeAttr("name");
                    $('#to').removeAttr("name");
                } else {
                    $('#from').prop('readonly',false);
                    $('#to').prop('readonly',false);
                    $('#from').attr("name","from");
                    $('#to').attr("name","to");
                }
            }

            function isToday()
            {
                if ($('#switchCompras').is(':checked')) {
                    $('#fromCompras').prop('readonly',true);
                    $('#toCompras').prop('readonly',true);
                    $('#fromCompras').removeAttr("name");
                    $('#toCompras').removeAttr("name");
                } else {
                    $('#fromCompras').prop('readonly',false);
                    $('#toCompras').prop('readonly',false);
                    $('#fromCompras').attr("name","from");
                    $('#toCompras').attr("name","to");
                }
            }
        });
    </script>

@endsection
