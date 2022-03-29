@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js">
    </script>

        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" id="codigo" placeholder="Buscar en sucursales">
                        </div>
                        <div class="col">
                            <button id="search" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#busquedaP"><i class="bi bi-search"></i></button>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#inventarioModal">Agregar</button>
                        </div>
                        <div class="col-2">
                            @php
                                $enum = 0;
                                $enum = sizeof($carritoCompras);
                            @endphp
                            <button class="btn btn-outline-success" type="button" data-bs-toggle="modal"
                                data-bs-target="#carritoCompraModal">Comprar +{{ $enum }}</button>
                        </div>
                        <div class="col-2">
                            @php
                                $num = 0;
                                $num = sizeof($carrito);
                            @endphp
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal"
                                data-bs-target="#carritoModal">Vender +{{ $num }}</button>
                        </div>
                        <div class="col-2">
                            <a href="{{ route('getOrder') }}" target="blank" type="button"
                                class="btn btn-outline-warning"><i class="bi bi-receipt-cutoff">Orden</i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control" id="inputBusqueda" placeholder="Buscar en Inventario">
                            </div>
                            <div class="col">
                                <button class="btn btn-outline-success" type="button" id="buscarInve"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <table class="table table-hover" id="tabla1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categoria</th>
                                        <th>Marca</th>
                                        <th>Stock</th>
                                        <th>Precio</th>
                                        <th>Costo</th>
                                        <th>Acciones</th>
                                        <th>Edición</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="inventarios">
                                    @forelse ($inventarios as $inventario)
                                        <tr>
                                            <td>{{ $inventario->id }}</td>
                                            <td>{{ $inventario->bar_code }}</td>
                                            <td>{{ $inventario->name }}</td>
                                            <td>{{ $inventario->categoria->name }}</td>
                                            <td>{{ $inventario->marca->name }}</td>
                                            <td>{{ $inventario->stock }}</td>
                                            <td>${{ $inventario->price }}</td>
                                            <td>${{ $inventario->cost }}</td>
                                            <td>
                                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                    data-bs-target="#addInventario{{ $inventario->id }}"><i
                                                        class="bi bi-bag-plus-fill"></i></button>
                                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                                    data-bs-target="#addCompra{{ $inventario->id }}"><i
                                                        class="bi bi-bag-plus"></i></button>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modaledit{{$inventario->id}}"><i class="bi bi-pencil"></i></button>
                                                <a href="{{route('codigoAlmacen', $inventario)}}" target="blank" type="button" class="btn btn-outline-primary"><i class="bi bi-upc"></i></a>
                                            </td>
                                            <td>
                                                <form action="{{route('inventario.delete',$inventario->id)}}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                            </table>
                            {{$inventarios->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>



@endsection
