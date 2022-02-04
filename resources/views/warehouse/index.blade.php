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


    @if (Auth::user()->rol_id == 1)
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4 class="card-title">Almacenes</h4>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Crear
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Oficina</th>
                                    <th>Encargado</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($almacenes as $almacen)
                                    <tr>
                                        <td>{{ $almacen->id }}</td>
                                        <td>{{ $almacen->oficina->name }}</td>
                                        <td>{{ $almacen->user->name }} {{ $almacen->user->last_name }}</td>
                                        @if ($almacen->status == true)
                                            <td style="color: rgb(5, 182, 5)">Activo</td>
                                        @else
                                            <td style="color: red">Inactivo</td>
                                        @endif
                                        <td>
                                            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{ $almacen->id }}"><i
                                                    class="bi bi-pencil"></i></button>
                                            @if ($almacen->status == true)
                                                <form action="{{ route('almacen.status', $almacen->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success"><i
                                                            class="bi bi-toggle-on"></i></button>
                                                </form>
                                            @else
                                                <form action="{{ route('almacen.status', $almacen->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-danger"><i
                                                            class="bi bi-toggle-off"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="exampleModal{{ $almacen->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="{{ route('almacen.update', $almacen) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Editar almacén
                                                            #{{ $almacen->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="">Usuario</label>
                                                                <select name="user_id" id="user_id" required
                                                                    class="form-control">
                                                                    <option value="">--Seleccionar--</option>
                                                                    @forelse ($usuarios as $user)
                                                                        @if ($user->id == $almacen->user_id)
                                                                            <option value="{{ $user->id }}" selected>
                                                                                {{ $user->name }}
                                                                                {{ $user->last_name }}</option>
                                                                        @else
                                                                            <option value="{{ $user->id }}">
                                                                                {{ $user->name }} {{ $user->last_name }}
                                                                            </option>
                                                                        @endif
                                                                    @empty
                                                                        <option>Sin usuarios registrados</option>
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p><b>**Nota**</b> El usuario previamente creado y asignado al
                                                            almacén ya podrá ingresar al sistema y administrar el mismo.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td style="align-content: center" colspan="5">Esta sucursal no cuenta con almacén
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $almacenes->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('almacen.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crear almacén</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p style="color: red">El siguiente formulario es para crear el almacén virtual y asignar al
                                responsable del mismo.</p>
                            <div class="row">
                                <div class="col">
                                    <label for="">Usuario</label>
                                    <select name="user_id" id="user_id" required class="form-control">
                                        <option value="">--Seleccionar--</option>
                                        @forelse ($usuarios as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                {{ $user->last_name }}</option>
                                        @empty
                                            <option>Sin usuarios registrados</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <p><b>**Nota**</b> El usuario previamente creado y asignado al almacén ya podrá ingresar al
                                sistema y administrar el mismo.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
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
                            <table class="table table-hover">
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
        @forelse ($invetories as $inventario)
            <div class="modal fade" id="modaledit{{ $inventario->id }}" tabindex="-1"
                aria-labelledby="modaledit{{ $inventario->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('inventario.update', $inventario->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="modal-header">
                                <h5 class="modal-title">Editar producto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <label for="">Código de barra</label>
                                        <input type="text" class="form-control" name="bar_code" value="{{$inventario->bar_code}}" required>
                                    </div>
                                    <div class="col">
                                        <label for="">Nombre</label>
                                        <input type="text" class="form-control" name="name" value="{{$inventario->name}}" required>
                                    </div>
                                    <div class="col">
                                        <label for="">Categoria</label>
                                        <select name="category_id" id="" class="form-control" required>
                                            @forelse ($categorias as $categoria)
                                                @if ($categoria->id == $inventario->category_id)
                                                    <option selected value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                                                @else
                                                    <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                                                @endif
                                            @empty
                                                <option value="">Sin categorias</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">Marca</label>
                                        <select name="brand_id" id="" class="form-control" required>
                                            @forelse ($marcas as $marca)
                                                @if ($marca->id == $inventario->brand_id)
                                                    <option selected value="{{ $marca->id }}">{{ $marca->name }}</option>
                                                @else
                                                    <option value="{{ $marca->id }}">{{ $marca->name }}</option>
                                                @endif
                                            @empty

                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="">Stock</label>
                                        <input type="number" class="form-control" name="stock" value="{{$inventario->stock}}">
                                    </div>
                                    <div class="col">
                                        <label for="">Precio</label>
                                        <input type="numer" class="form-control" step="any" name="price" value="{{$inventario->price}}">
                                    </div>
                                    <div class="col">
                                        <label for="">Costo</label>
                                        <input type="number" class="form-control" step="any" name="cost" value="{{$inventario->cost}}">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>


            <div class="modal fade" id="addInventario{{ $inventario->id }}" tabindex="-1"
                aria-labelledby="addInventario{{ $inventario->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('add', $inventario->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Añadir al carrito</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <label for="">Producto</label>
                                        <input type="text" required readonly
                                            value="{{ $inventario->name }}" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="">Precio</label>
                                        <input type="number" class="form-control" step="any"
                                            required readonly value="{{ $inventario->price }}"
                                            id="price{{ $inventario->id }}">
                                    </div>
                                    <div class="col">
                                        <label for="">Cantidad</label>
                                        <input name="quantity" type="number"
                                            class="form-control cantidad" required
                                            data-id="{{ $inventario->id }}" min="1"
                                            max="{{ $inventario->stock }}" value="1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">Sub Total</label>
                                        <input type="number" class="form-control" step="any"
                                            id="subtotal{{ $inventario->id }}"
                                            value="{{ $inventario->price }}" readonly
                                            name="subtotal">
                                    </div>
                                    <div class="col">
                                        <label for="">Descuento en %</label>
                                        <input name="discount" type="number"
                                            data-id="{{ $inventario->id }}"
                                            class="form-control descuento" step="any"
                                            id="descuento{{ $inventario->id }}" value="0" min="0">
                                    </div>
                                    <div class="col">
                                        <label for="">Total</label>
                                        <input name="total" type="number" step="any"
                                            class="form-control" id="total{{ $inventario->id }}"
                                            value="{{ $inventario->price }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal fade" id="addCompra{{ $inventario->id }}" tabindex="-1"
                aria-labelledby="addInventario{{ $inventario->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('addCompra', $inventario->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Añadir al carrito</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <label for="">Producto</label>
                                        <input type="text" required readonly
                                            value="{{ $inventario->name }}"
                                            class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="">Costo</label>
                                        <input type="number" class="form-control" step="any"
                                            required readonly value="{{ $inventario->cost }}"
                                            id="cost{{ $inventario->id }}">
                                    </div>
                                    <div class="col">
                                        <label for="">Cantidad</label>
                                        <input name="quantity" type="number"
                                            class="form-control cantidadCompra" required
                                            data-id="{{ $inventario->id }}" min="1"
                                            max="{{ $inventario->stock }}" value="1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">Sub Total</label>
                                        <input type="number" class="form-control" step="any"
                                            id="subtotalCompra{{ $inventario->id }}"
                                            value="{{ $inventario->cost }}" readonly
                                            name="subtotal">
                                    </div>
                                    <div class="col">
                                        <label for="">Descuento en %</label>
                                        <input name="discount" type="number"
                                            data-id="{{ $inventario->id }}"
                                            class="form-control descuentoCompra" step="any"
                                            id="descuentoCompra{{ $inventario->id }}" value="0"
                                            min="0">
                                    </div>
                                    <div class="col">
                                        <label for="">Total</label>
                                        <input name="total" type="number" step="any"
                                            class="form-control"
                                            id="totalCompra{{ $inventario->id }}"
                                            value="{{ $inventario->cost }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        @empty

        @endforelse

        <div class="modal fade" id="inventarioModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('inventario.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <label for="">Código</label>
                                    <input type="text" class="form-control" required name="bar_code">
                                </div>
                                <div class="col">
                                    <label for="">Nombre</label>
                                    <input type="text" class="form-control" required name="name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="">Stock</label>
                                    <input type="number" class="form-control" required name="stock">
                                </div>
                                <div class="col">
                                    <label for="">Costo</label>
                                    <input type="number" step="any" class="form-control" required name="cost">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="">Precio</label>
                                    <input type="numer" step="any" class="form-control" required name="price">
                                </div>
                                <div class="col">
                                    <label for="">Marca</label>
                                    <select name="brand_id" id="" required class="form-control">
                                        <option value="">--Seleccionar--</option>
                                        @forelse ($marcas as $marca)
                                            <option value="{{ $marca->id }}">{{ $marca->name }}</option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="">Categoria</label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">--Seleccionar--</option>
                                        @forelse ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="carritoModal" tabindex="-1" aria-labelledby="addInventario" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('concluir') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Carrito</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
                                            <th>Sub Total</th>
                                            <th>Descuento %</th>
                                            <th>Total</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablita">
                                        @php
                                            $total = 0;
                                        @endphp
                                        @forelse ($carrito as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->inventario[0]->name ?? '' }}</td>
                                                <td>${{ $item->inventario[0]->price ?? 0 }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>${{ $item->subtotal }}</td>
                                                <td>{{ $item->discount }}%</td>
                                                <td>${{ $item->total }}</td>
                                                <td>
                                                    <button class="btn btn-outline-danger delete" type="button"
                                                        data-id="{{ $item->id }}"><i
                                                            class="bi bi-trash-fill"></i></button>
                                                </td>
                                            </tr>
                                            @php
                                                $total += $item->total;
                                            @endphp
                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <label for="">Sucursal</label>
                                        <select name="office_id" id="" class="form-control" required>
                                            <option value="">--Seleccionar--</option>
                                            @forelse ($oficinas as $oficina)
                                                @if ($oficina->id == Auth::user()->branch_office_id)
                                                    <option selected value="{{ $oficina->id }}">{{ $oficina->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $oficina->id }}">{{ $oficina->name }}</option>
                                                @endif
                                            @empty

                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="">Tipo Pago</label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="">--Seleccionar--</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                            <option value="Transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="">Sub Total</label>
                                        <input type="number" name="subtotal" class="form-control" step="any"
                                            id="subtotalGeneral" readonly value="{{ $total }}">
                                    </div>
                                    <div class="col">
                                        <label for="">Descuento %</label>
                                        <input type="number" name="discount" value="0" min="0" class="form-control"
                                            step="any" max="100" id="descuentoGeneral">
                                    </div>
                                    <div class="col">
                                        <label for="">Total</label>
                                        <input type="number" name="total" class="form-control" step="any"
                                            id="totalGeneral" readonly value="{{ $total }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Transferir</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>


        <div class="modal fade" id="carritoCompraModal" tabindex="-1" aria-labelledby="addInventario"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('concluir.compra') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Carrito de Compra</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
                                            <th>Sub Total</th>
                                            <th>Descuento %</th>
                                            <th>Total</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablita">
                                        @php
                                            $total = 0;
                                        @endphp
                                        @forelse ($carritoCompras as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->inventario[0]->name }}</td>
                                                <td>${{ $item->inventario[0]->price }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>${{ $item->subtotal }}</td>
                                                <td>{{ $item->discount }}%</td>
                                                <td>${{ $item->total }}</td>
                                                <td>
                                                    <button class="btn btn-outline-danger eliminar" type="button"
                                                        data-id="{{ $item->id }}"><i
                                                            class="bi bi-trash-fill"></i></button>
                                                </td>
                                            </tr>
                                            @php
                                                $total += $item->total;
                                            @endphp
                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <label for="">Sucursal</label>
                                        <select name="office_id" id="" class="form-control" required>
                                            <option selected value="{{ Auth::user()->branch_office_id }}">
                                                {{ Auth::user()->branchOffice->name }}</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="">Tipo Pago</label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="">--Seleccionar--</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                            <option value="Transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="">Sub Total</label>
                                        <input type="number" name="subtotal" class="form-control" step="any"
                                            id="subtotalGeneralCompra" readonly value="{{ $total }}">
                                    </div>
                                    <div class="col">
                                        <label for="">Descuento %</label>
                                        <input type="number" name="discount" value="0" min="0" class="form-control"
                                            step="any" max="100" id="descuentoGeneralCompra">
                                    </div>
                                    <div class="col">
                                        <label for="">Total</label>
                                        <input type="number" name="total" class="form-control" step="any"
                                            id="totalGeneralCompra" readonly value="{{ $total }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Comprar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="modal fade" id="busquedaP" tabindex="-1" aria-labelledby="addInventario" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('inventario.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar producto a Inventario</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <label for="">Código de Barras</label>
                                        <input type="text" class="form-control" required id="bar_code" name="bar_code">
                                    </div>
                                    <div class="col">
                                        <label for="">Nombre</label>
                                        <input type="text" class="form-control" required id="name" name="name">
                                    </div>
                                    <div class="col">
                                        <label for="">Categoria</label>
                                        <select name="category_id" id="category_ide" class="form-control" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="">Marca</label>
                                        <select name="brand_id" id="brand_id" class="form-control" required>

                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="">Stock</label>
                                        <input type="number" class="form-control" min="0" required name="stock"
                                            id="stock">
                                    </div>
                                    <div class="col">
                                        <label for="">Costo</label>
                                        <input type="numer" step="any" class="form-control" name="cost" id="cost"
                                            required>
                                    </div>
                                    <div class="col">
                                        <label for="">Precio</label>
                                        <input type="number" step="any" class="form-control" name="price" id="price"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('.cantidad').on('change', function() {
                    var id = $(this).data('id');
                    var precio = $('#price' + id).val();
                    var cantidad = $(this).val();
                    var subtotal = cantidad * precio;
                    var descuento = $('#descuento' + id).val();
                    $('#subtotal' + id).val(subtotal);
                    var porcentaje = descuento / 100;
                    var total = subtotal - (subtotal * porcentaje);
                    $('#total' + id).val(total);
                });

                $('.descuento').on('change', function() {
                    var id = $(this).data('id');
                    var descuento = $(this).val();
                    var subtotal = $('#subtotal' + id).val();
                    var porcentaje = descuento / 100;
                    var total = subtotal - (subtotal * porcentaje);
                    $('#total' + id).val(total);
                });

                $('.descuento').keyup(function(e) {
                    var id = $(this).data('id');
                    var descuento = $(this).val();
                    var subtotal = $('#subtotal' + id).val();
                    var porcentaje = descuento / 100;
                    var total = subtotal - (subtotal * porcentaje);
                    $('#total' + id).val(total);
                });

                $.get('/getCarrito', function(data) {

                });

                $('#descuentoGeneral').keyup(function(e) {
                    var descuento = $(this).val();
                    var porcentaje = descuento / 100;
                    var subtotal = $('#subtotalGeneral').val();
                    var total = subtotal - (subtotal * porcentaje);
                    $('#totalGeneral').val(total.toFixed(2));
                });

                $('#descuentoGeneral').on('change', function() {
                    var descuento = $(this).val();
                    var porcentaje = descuento / 100;
                    var subtotal = $('#subtotalGeneral').val();
                    var total = subtotal - (subtotal * porcentaje);
                    $('#totalGeneral').val(total.toFixed(2));
                });



                $('.cantidadCompra').on('change', function() {
                    var id = $(this).data('id');
                    var precio = $('#cost' + id).val();
                    var cantidad = $(this).val();
                    var subtotal = cantidad * precio;
                    var descuento = $('#descuentoCompra' + id).val();
                    $('#subtotalCompra' + id).val(subtotal);
                    var porcentaje = descuento / 100;
                    var total = subtotal - (subtotal * porcentaje);
                    $('#totalCompra' + id).val(total);
                });

                $('.descuentoCompra').on('change', function() {
                    var id = $(this).data('id');
                    var descuento = $(this).val();
                    var subtotal = $('#subtotalCompra' + id).val();
                    var porcentaje = descuento / 100;
                    var total = subtotal - (subtotal * porcentaje);
                    $('#totalCompra' + id).val(total);
                });

                $('.descuentoCompra').keyup(function(e) {
                    var id = $(this).data('id');
                    var descuento = $(this).val();
                    var subtotal = $('#subtotalCompra' + id).val();
                    var porcentaje = descuento / 100;
                    var total = subtotal - (subtotal * porcentaje);
                    $('#totalCompra' + id).val(total);
                });


                $('#descuentoGeneralCompra').keyup(function(e) {
                    var descuento = $(this).val();
                    var porcentaje = descuento / 100;
                    var subtotal = $('#subtotalGeneralCompra').val();
                    var total = subtotal - (subtotal * porcentaje);
                    $('#totalGeneralCompra').val(total.toFixed(2));
                });

                $('#descuentoGeneralCompra').on('change', function() {
                    var descuento = $(this).val();
                    var porcentaje = descuento / 100;
                    var subtotal = $('#subtotalGeneralCompra').val();
                    var total = subtotal - (subtotal * porcentaje);
                    $('#totalGeneralCompra').val(total.toFixed(2));
                });

                $('#search').on('click', function() {
                    var codigo = $('#codigo').val();

                    $.get('/buscar-cdigo/' + codigo, function(data) {
                        if (data['id'] > 0) {
                            console.log(data);
                            $('#bar_code').val(data['bar_code']);
                            $('#name').val(data['name']);
                            $('#category_ide').empty();
                            $('#brand_id').empty();
                            $('#category_ide').append("<option value='" + data['categoria']['id'] +
                                "'>" + data['categoria']['name'] + "</option>");
                            $('#brand_id').append("<option value='" + data['brand']['id'] + "'>" + data[
                                'brand']['name'] + "</option>");
                            $('#cost').val(data['cost']);
                            $('#price').val(data['price_1']);
                        } else {
                            alert("No se encontro ningun producto");
                        }

                    });
                });

                $('.delete').on('click', function() {
                    var id = $(this).data('id');
                    var ajxReq = $.ajax('/eliminar/'+id, {
                        type: 'DELETE'
                    });
                    ajxReq.success(function(data, status, jqXhr) {
                        alert('Eliminado con exito');
                        location.reload();
                    });
                    ajxReq.error(function(jqXhr, textStatus, errorMessage) {
                        alert('Error');
                        location.reload();
                    });
                });

                $('.eliminar').on('click', function() {
                    var id = $(this).data('id');
                    var ajxReq = $.ajax('/delete-cart/'+id, {
                        type: 'DELETE'
                    });
                    ajxReq.success(function(data, status, jqXhr) {
                        alert('Eliminado con exito');
                        location.reload();
                    });
                    ajxReq.error(function(jqXhr, textStatus, errorMessage) {
                        alert('Error');
                        location.reload();
                    });
                });

                $('#buscarInve').on('click',function(){
                    var palabra = $('#inputBusqueda').val();
                    $.get('/buscarInventario/'+palabra,function (data){
                        console.log(data);
                        $('#inventarios').empty();
                        data.forEach(element => {
                            $('#inventarios').append("<tr>"+
                                "<td>"+element['id']+"</td>"+
                                "<td>"+element['bar_code']+"</td>"+
                                "<td>"+element['name']+"</td>"+
                                "<td>"+element['categoria']['name']+"</td>"+
                                "<td>"+element['marca']['name']+"</td>"+
                                "<td>"+element['stock']+"</td>"+
                                "<td>"+element['price']+"</td>"+
                                "<td>"+element['cost']+"</td>"+
                                "<td>"+
                                    "<button type='button' class='btn btn-outline-secondary' data-bs-toggle='modal' data-bs-target='#addInventario"+element['id']+"'><i class='bi bi-bag-plus-fill'></i></button>"+
                                    "<button type='button' class='btn btn-outline-success' data-bs-toggle='modal' data-bs-target='#addCompra"+element['id']+"'><i class='bi bi-bag-plus'></i></button>"+
                                "</td>"+
                                "<td>"+
                                    "<button class='btn btn-outline-primary' type='button' data-bs-toggle='modal' data-bs-target='#modaledit"+element['id']+"'><i class='bi bi-pencil'></i></button>"+
                                "</td>"+
                            "</tr>");
                        });
                    });
                });


            });
        </script>

    @endif

@endsection
