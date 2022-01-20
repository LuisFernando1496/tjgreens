@extends('layouts.app')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<div class="container">
    <!-- Create Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Nuevo producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="guardarData">
                    <form onsubmit="upperCreate()" id="sendData">
                    <!--<form id="myForm" action="/product" enctype="multipart/form-data" method="post" onsubmit="upperCreate()">-->
                        <!--@csrf-->
                        <div class="form-group my-3 mx-3">
                            <label for="bar_code">Código de barras</label>
                            <input class="form-control" type="text" name="bar_code" id="bar_code" placeholder="Código de barras" required>
                            <div data-toggle="tooltip_bar_code" class="alert-danger"></div>
                        </div>
                        <!-- <div class="form-group my-3 mx-3">
                            <label for="image">Imagen del producto</label>
                            <input type="file" class="form-control-file" name="image" id="image" accept=".jpg,.png,.jpeg">
                        </div> -->
                        <div class="form-group  my-3 mx-3">
                            <label for="name">Nombre</label>
                            <input  style="text-transform: uppercase" class="form-control" type="text" name="name" id="name" placeholder="Nombre" required>
                            <div data-toggle="tooltip_name" class="alert-danger"></div>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="stock">Stock</label>
                            <input class="form-control" type="number" name="stock" id="stock" placeholder="Stock" required>
                            <div data-toggle="tooltip_stock" class="alert-danger"></div>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="price">Costo</label><br>
                            <input class="form-control"  step="any" type="number" name="cost" id="cost" placeholder="Costo" required>
                            <div data-toggle="tooltip_cost" class="alert-danger"></div>
                        </div>

                        <div class="form-group my-3 mx-3">
                            <label for="expiration">Caducidad</label>
                            <input class="form-control" type="date" name="expiration" id="expiration" placeholder="25/09/2021" >
                            <div data-toggle="tooltip_expiration" class="alert-danger"></div>
                        </div>

                        <div class="form-group my-3 mx-3">
                            <label for="price">Precio 1</label>
                            <input class="form-control" step="any" type="number" name="price_1" id="price_1" placeholder="Precio 1" required>
                            <div data-toggle="tooltip_price_1" class="alert-danger"></div>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="price">Precio 2</label>
                            <input class="form-control"  step="any" type="number" name="price_2" id="price_2" placeholder="Precio 2">
                            <div data-toggle="tooltip_price_2" class="alert-danger"></div>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="price">Precio 3</label>
                            <input class="form-control"  step="any" type="number" name="price_3" id="price_3" placeholder="Precio 3">
                            <div data-toggle="tooltip_price_3" class="alert-danger"></div>
                        </div>
                         {{-- <div class="form-group my-3 mx-3">
                            <label for="iva">Iva</label>
                            <input class="form-control" type="number" step="any" name="iva" id="iva" placeholder="1.6" required>
                            <div data-toggle="tooltip_iva" class="alert-danger"></div>
                        </div>
                       <div class="form-group my-3 mx-3">
                            <label for="product_key">Clave de producto</label>
                            <input class="form-control" type="text" name="product_key" id="product_key" placeholder="DSA4A7" required>
                            <div data-toggle="tooltip_product_key" class="alert-danger"></div>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="unit_product_key">Clave de unidad del producto</label>
                            <input class="form-control" type="text" name="unit_product_key" id="unit_product_key" placeholder="HD5" required>
                            <div data-toggle="tooltip_unit_product_key" class="alert-danger"></div>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="lot">Lote</label>
                            <input class="form-control" type="text" name="lot" id="lot" placeholder="lote" required>
                            <div data-toggle="tooltip_lot" class="alert-danger"></div>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="ieps">IEPS</label>
                            <input class="form-control" type="text" name="ieps" id="ieps" placeholder="ieps" required>
                            <div data-toggle="tooltip_ieps" class="alert-danger"></div>
                        </div>--}}
                        {{-- solo si es admin --}}
                        <div class="form-group my-3-mx-3">
                            <label for="branch_office_id">Sucursal</label>
                            <select class="browser-default custom-select form-control" name="branch_office_id" id="branch_office_id" required>
                                <option value="" selected hidden>Sucursal</option>
                                @foreach ($offices as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <div data-toggle="tooltip_branch_office_id" class="alert-danger"></div>
                        </div>
                        <div class="form-group my-3-mx-3">
                            <label for="category_id">Categoria</label>
                            <select class="browser-default custom-select form-control" name="category_id" id="category_id" required>
                                <option value="" selected hidden>Categoria</option>
                                @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <div data-toggle="tooltip_category_id" class="alert-danger"></div>
                        </div>
                        <div class="form-group my-3-mx-3">
                            <label for="brand_id">Marca</label>
                            <select class="browser-default custom-select form-control" name="brand_id" id="brand_id" required>
                                <option value="" selected hidden>Marca</option>
                                @foreach ($brands as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <div data-toggle="tooltip_brand_id" class="alert-danger"></div>
                        </div>
                        <div class="form-group my-3-mx-3">
                            <label for="provider_id">Proveedor</label>
                            <select class="browser-default custom-select form-control" name="provider_id" id="provider_id" required>
                                <option value="" selected hidden>Proveedor</option>
                                @foreach ($providers as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <div data-toggle="tooltip_provider_id" class="alert-danger"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn  btn-outline-primary" id="btnGuardar">Guardar</button>
                            <!--<button type="button" class="btn btn-outline-primary" id="btnGuardar" name="btnGuardar" onclick="guardarDatos()">Guardar</button>-->
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="productModalEdit" tabindex="-1" role="dialog" aria-labelledby="productModalEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalEditLabel">Editar producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myFormEdit" action="/product" method="post" enctype="multipart/form-data" onsubmit="upperCreate()">
                        @csrf
                        <div class="form-group my-3 mx-3">
                            <label for="bar_code">Código de barras</label>
                            <input class="form-control" type="text" name="bar_code" id="bar_code_edit" placeholder="Código de barras" required>
                        </div>
                        <!-- <div class="form-group my-3 mx-3">
                            <label for="image">Imagen del producto</label>
                            <input type="file" class="form-control-file" name="image" id="image_edit" accept=".jpg,.png,.jpeg">
                        </div> -->
                        <div class="form-group  my-3 mx-3">
                            <label for="name">Nombre</label>
                            <input  style="text-transform: uppercase" class="form-control" type="text" name="name" id="name_edit" placeholder="Nombre" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="stock">Stock</label>
                            <input class="form-control" type="number" name="stock" id="stock_edit" placeholder="Stock" required>
                        </div>

                        <div class="form-group my-3 mx-3">
                            <label for="price">Costo</label><br>
                            <!--<label for="rate">¿Costo en dolares?  <input type="checkbox" name="dollar" id="dollar" value="1"></label>-->
                            <input class="form-control"  step="any" type="number" name="cost" id="cost_edit" placeholder="Costo" required>
                        </div>

                        <div class="form-group my-3 mx-3">
                            <label for="expiration">Caducidad</label>
                            <input class="form-control" type="date" name="expiration" id="expiration_edit" placeholder="25/09/2021" >
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="price">Precio 1</label>
                            <input class="form-control" type="number" name="price_1" id="price_1_edit" placeholder="Precio 1" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="price">Precio 2</label>
                            <input class="form-control" type="number" name="price_2" id="price_2_edit" placeholder="Precio 2">
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="price">Precio 3</label>
                            <input class="form-control" type="number" name="price_3" id="price_3_edit" placeholder="Precio 3">
                        </div>
                     {{--    <div class="form-group my-3 mx-3">
                            <label for="iva">Iva</label>
                            <input class="form-control" type="number" step="any" name="iva" id="iva_edit" placeholder="1.6" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="product_key">Clave de producto</label>
                            <input class="form-control" type="text" name="product_key" id="product_key_edit" placeholder="DSA4A7" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="unit_product_key">Clave de unidad del producto</label>
                            <input class="form-control" type="text" name="unit_product_key" id="unit_product_key_edit" placeholder="HD5" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="lot">Lote</label>
                            <input class="form-control" type="text" name="lot" id="lot_edit" placeholder="lote" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="ieps">IEPS</label>
                            <input class="form-control" type="text" name="ieps" id="ieps_edit" placeholder="" required>
                        </div>--}}
                        {{-- solo si es admin --}}
                        <div class="form-group my-3-mx-3">
                            <select class="browser-default custom-select" name="branch_office_id" id="branch_office_id_edit" required>
                                <option value="" selected hidden>Sucursal</option>
                                @foreach ($offices as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group my-3-mx-3">
                            <select class="browser-default custom-select" name="category_id" id="category_id_edit" required>
                                <option value="0" selected hidden>Categoria</option>
                                @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group my-3-mx-3">
                            <select class="browser-default custom-select" name="brand_id" id="brand_id_edit" required>
                                <option value="" selected hidden>Marca</option>
                                @foreach ($brands as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group my-3-mx-3">
                            <select class="browser-default custom-select" name="provider_id" id="provider_id_edit" required>
                                <option value="" selected hidden>Proveedor</option>
                                @foreach ($providers as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn  btn-outline-primary">Guardar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div id="alertsuccess"></div>
    @if($errors->any())
      @foreach($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{$error}}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endforeach
    @endif
    <div id="alerterror"></div>
    <div style="text-align:right">
        <button onclick="limpiar()" type="button" class="btn  btn-outline-primary my-2" data-toggle="modal" data-target="#productModal"><small>CREAR</small></button>
    </div>


    <!--<table class="display table table-striped table-bordered" id="example" style="width:100%">-->
    <div class="col-md-8">
        <div class="input-group">
            <input type="text" id="search" style="text-transform: uppercase" class="form-control" name="search" autocomplate="search" placeholder="Buscar producto"/>
            <div class="input-group-append">
                <button id="searchButton" class="btn btn-outline-secondary">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                        <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <table class="display table table-striped table-bordered" style="width:100%" id="tabla2">
        <thead class="black white-text">
            <tr>
                <th scope="col">Codigo de barras</th>
                <th scope="col">Nombre</th>
                <th scope="col">Stock</th>
                <th scope="col">Costo</th>
                <th scope="col">Precio 1</th>
                <th scope="col">Precio 2</th>
                <th scope="col">Precio 3</th>
                <th scope="col">IVA</th>
                <th scope="col">Categoria</th>
                <th scope="col">Marca</th>
                @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                <th scope="col">Sucursal</th>
                @endif
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="result2">
        </tbody>
    </table>
    <table class="display table table-striped table-bordered" style="width:100%" id="tabla1">
        <thead class="black white-text">
            <tr>
                <th scope="col">Codigo de barras</th>
                <th scope="col">Nombre</th>
                <th scope="col">Stock</th>
                <th scope="col">Costo</th>
                <th scope="col">Precio 1</th>
                <th scope="col">Precio 2</th>
                <th scope="col">Precio 3</th>
                <th scope="col">IVA</th>
                <th scope="col">Categoria</th>
                <th scope="col">Marca</th>
                @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                <th scope="col">Sucursal</th>
                @endif
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="result">
            @foreach ($products as $item)
                <tr>
                    <th scope="row">{{$item->bar_code}}</th>
                    <td>{{$item->name}}</td>
                    <td>{{$item->stock}}</td>
                    <td>${{$item->cost}}</td>
                    <td>${{$item->price_1}}</td>
                    @if($item->price_2)
                        <td>${{$item->price_2}}</td>
                    @else
                        <td>----</td>
                    @endif
                    @if($item->price_3)
                        <td>${{$item->price_3}}</td>
                    @else
                        <td>---</td>
                    @endif

                    @if($item->iva == null)
                        <td>-</td>
                    @else
                        <td>{{$item->iva}}</td>
                    @endif
                    <td>{{$item->category->name}}</td>
                    <td>{{$item->brand->name ?? '-'}}</td>
                    @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                        @if($item->branch_office == null)
                            <td>-</td>
                        @else
                            <td>{{$item->branch_office->name}}</td>
                        @endif
                    @endif
                    <td>
                        <button onclick="llenar({{$item}})" type="button" class="btn btn-outline-secondary btn-sm my-2" data-type="edit" data-toggle="modal" data-target="#productModalEdit">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                            </svg>
                            </button>
                        <form onsubmit="return confirm('Eliminar producto?')" action="/product/{{$item->id}}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger btn-sm my-2" data-type="delete">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                                </svg>
                            </button>
                        </form>
                        <a href="{{route('tag',$item)}}" target="blank" type="button" class="btn btn-outline-primary"><i class="bi bi-upc"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
{{ $products->links() }}<!--paginar la tabla desde la base de datos-->
@endsection
@push('scripts')
<!--<script>-->
<script type="application/javascript">
    //console.log("aqui");
    let result = [];
    window.addEventListener("load",function(){
        //console.log("aqui2");
        $("#tabla2").prop('hidden', true);
        document.getElementById("search").addEventListener("keyup", function(){
            if (document.getElementById("search").value.length >= 1){
                $("#tabla1").prop('hidden', true);
                $("#tabla2").prop('hidden', false);
                fetch(`products/busqueda?search=${document.getElementById("search").value.toUpperCase()}`,{
                    method: 'get',
                    headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content') }
                }).then(response => response.text())
                .then(text => {
                    document.getElementById("result2").innerHTML = "";
                    console.log(typeof(text));
                    result=JSON.parse(text);
                    //console.log(result.data[0]);
                    //r = JSON.parse(result.data[0]);
                    //console.log(r);
                    result.data.forEach(function(element,index){
                        console.log(result.data[index]);
                        document.getElementById("result2").innerHTML += //'<tr>'+
                                '<tr class="item-resultC" style="cursor: grab;" data-id="'+element.id+'">'+
                                '<td>'+element.bar_code+'</td>'+
                                '<td>'+element.name+'</td>'+
                                '<td>'+element.stock+'</td>'+
                                '<td>'+element.cost+'</td>'+
                                '<td>'+element.price_1+'</td>'+
                                '<td>'+element.price_2+'</td>'+
                                '<td>'+element.price_3+'</td>'+
                                '<td>'+element.iva+'</td>'+
                                '<td>'+element.categories_name+'</td>'+
                                '<td>'+element.brands_name+'</td>'+
                                '<td>'+element.branch_office_name+'</td>'+
                                '<td>'+
                                    '<button onclick="llenar2('+element.id+')" type="button" class="btn btn-outline-secondary btn-sm my-2" data-type="edit" data-toggle="modal" data-target="#productModalEdit">'+
                                        '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
                                            '<path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>'+
                                        '</svg>'+
                                    '</button>'+
                                    '<form onsubmit="return confirm(`Eliminar producto?`)" action="/product/'+element.id+'" method="post">'+
                                        '@csrf'+
                                        '@method("delete")'+
                                        '<button type="submit" class="btn btn-outline-danger btn-sm my-2" data-type="delete">'+
                                            '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
                                                '<path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>'+
                                            '</svg>'+
                                        '</button>'+
                                    '</form>'+
                                    '<a href="/tag/'+element.id+'" target="blank" type="button" class="btn btn-outline-primary"><i class="bi bi-upc"></i></a>'+
                                '</td>'+
                            '</tr>';
                    });

                });
                //.catch(error => console.log(error));
            }else{
                $("#tabla1").prop('hidden', false);
                $("#tabla2").prop('hidden', true);
                document.getElementById("result2").innerHTML = ""
            }
        });
        document.getElementById("btnGuardar").addEventListener("click", function(){
            if($('#bar_code').val()==""){
                $('[data-toggle="tooltip_bar_code"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("bar_code").focus();
            }
            $('[data-toggle="tooltip_bar_code"]').tooltip().prop("hidden", true);
            if($('#name').val()==""){
                $('[data-toggle="tooltip_name"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("name").focus();
            }
            $('[data-toggle="tooltip_name"]').tooltip().prop("hidden", true);
            if($('#stock').val()==""){
                $('[data-toggle="tooltip_stock"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("stock").focus();
            }
            $('[data-toggle="tooltip_stock"]').tooltip().prop("hidden", true);
            if($('#cost').val()==""){
                $('[data-toggle="tooltip_cost"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("cost").focus();
            }
            $('[data-toggle="tooltip_cost"]').tooltip().prop("hidden", true);
            if($('#expiration').val()==""){
                $('[data-toggle="tooltip_expiration"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("expiration").focus();
            }
            $('[data-toggle="tooltip_expiration"]').tooltip().prop("hidden", true);
            if($('#iva').val()==""){
                $('[data-toggle="tooltip_iva"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("iva").focus();
            }
            $('[data-toggle="tooltip_iva"]').tooltip().prop("hidden", true);
            if($('#product_key').val()==""){
                $('[data-toggle="tooltip_product_key"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("product_key").focus();
            }
            $('[data-toggle="tooltip_product_key"]').tooltip().prop("hidden", true);
            if($('#unit_product_key').val()==""){
                $('[data-toggle="tooltip_unit_product_key"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("unit_product_key").focus();
            }
            $('[data-toggle="tooltip_product_key"]').tooltip().prop("hidden", true);
            if($('#lot').val()==""){
                $('[data-toggle="tooltip_unit_product_key"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("lot").focus();
            }
            $('[data-toggle="tooltip_unit_product_key"]').tooltip().prop("hidden", true);
            if($('#ieps').val()==""){
                $('[data-toggle="tooltip_ieps"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("ieps").focus();
            }
            $('[data-toggle="tooltip_ieps"]').tooltip().prop("hidden", true);
            if($('#price_1').val()==""){
                $('[data-toggle="tooltip_price_1"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("price_1").focus();
            }
            $('[data-toggle="tooltip_price_1"]').tooltip().prop("hidden", true);
            if($('#price_2').val()==""){
                $('[data-toggle="tooltip_price_2"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("price_2").focus();
            }
            $('[data-toggle="tooltip_price_2"]').tooltip().prop("hidden", true);
            if($('#price_3').val()==""){
                $('[data-toggle="tooltip_price_3"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("price_3").focus();
            }
            $('[data-toggle="tooltip_price_3"]').tooltip().prop("hidden", true);
            if($('#branch_office_id').val()==""){
                $('[data-toggle="tooltip_branch_office_id"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("branch_office_id").focus();
            }
            $('[data-toggle="tooltip_branch_office_id"]').tooltip().prop("hidden", true);
            if($('#category_id').val()==""){
                $('[data-toggle="tooltip_category_id"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("category_id").focus();
            }
            $('[data-toggle="tooltip_category_id"]').tooltip().prop("hidden", true);
            if($('#brand_id').val()==""){
                $('[data-toggle="tooltip_brand_id"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("brand_id").focus();
            }
            $('[data-toggle="tooltip_brand_id"]').tooltip().prop("hidden", true);
            if($('#provider_id').val()==""){
                $('[data-toggle="tooltip_provider_id"]').tooltip().text("Campo vacio, Llenar!");
                return document.getElementById("provider_id").focus();
            }
            $('[data-toggle="tooltip_provider_id"]').tooltip().prop("hidden", true);
            fetch(`products/guardar`,{
                method: 'POST',
                body: JSON.stringify({
                    name: $('#name').val().toUpperCase(),
                    stock: parseInt($('#stock').val()),
                    cost: parseInt($('#cost').val()),
                    expiration: $('#expiration').val(),
                    iva: parseInt($('#iva').val()),
                    product_key: parseInt($('#product_key').val()),
                    unit_product_key: parseInt($('#unit_product_key').val()),
                    lot: parseInt($('#lot').val()),
                    ieps: parseInt($('#ieps').val()),
                    price_1: parseInt($('#price_1').val()),
                    price_2: parseInt($('#price_2').val()),
                    price_3: parseInt($('#price_3').val()),
                    bar_code: $('#bar_code').val(),
                    branch_office_id: $('#branch_office_id').find(':selected').val(),
                    category_id: $('#category_id').find(':selected').val(),
                    brand_id: $('#brand_id').find(':selected').val(),
                    provider_id: $('#provider_id').find(':selected').val(),
                }),
                headers: {"Content-type": "application/json; charset=UTF-8"},
            }).then(response => response.text())
            .then(text => {
                $('#productModal').modal('hide');
                //console.log(typeof(text));
                result = JSON.parse(text);
                if(result.success){
                    document.getElementById("alertsuccess").innerHTML =
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">'+result.success+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span>'+
                        '</button>'+
                    '</div>';
                }else{
                    document.getElementById("alerterror").innerHTML =
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">'+result.error+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span>'+
                        '</button>'+
                    '</div>';
                }
            });
        });
    });

    function limpiar(){
        let fields = document.getElementsByClassName('form-control')

        let selects = document.getElementsByClassName('custom-select')

        for (let i = 0; i < selects.length; i++) {
            var element = selects[i];
            element.value = ''
        }

        for (let i = 0; i < fields.length; i++) {
            var element = fields[i];
            element.value = ''
        }
    }

    function llenar2(idP){
        let item = result.data.find(element => element.id == idP);
        console.log("a: "+item.category_id);
        document.getElementById("myFormEdit").action = "/product/"+item.id;
        document.getElementById('name_edit').value = item.name
        document.getElementById('stock_edit').value = item.stock
        document.getElementById('cost_edit').value = item.cost
        document.getElementById('price_1_edit').value = item.price_1
        document.getElementById('price_2_edit').value = item.price_2
        document.getElementById('price_3_edit').value = item.price_3
        document.getElementById('bar_code_edit').value = item.bar_code
        document.getElementById('branch_office_id_edit').value = item.branch_office_id
        document.getElementById('provider_id_edit').value = item.provider_id
        document.getElementById('category_id_edit').value = item.category_id
        document.getElementById('brand_id_edit').value = item.brand_id
        document.getElementById('expiration_edit').value = item.expiration
        document.getElementById('iva_edit').value = item.iva
        document.getElementById('product_key_edit').value = item.product_key
        document.getElementById('unit_product_key_edit').value = item.unit_product_key
        document.getElementById('lot_edit').value = item.lot
        document.getElementById('ieps_edit').value = item.ieps
    }

    function llenar(item){
        console.log("llenar: "+item.category_id);
        document.getElementById("myFormEdit").action = "/product/"+item.id;
        document.getElementById('name_edit').value = item.name
        document.getElementById('stock_edit').value = item.stock
        document.getElementById('cost_edit').value = item.cost
        document.getElementById('price_1_edit').value = item.price_1
        document.getElementById('price_2_edit').value = item.price_2
        document.getElementById('price_3_edit').value = item.price_3
        document.getElementById('bar_code_edit').value = item.bar_code
        document.getElementById('branch_office_id_edit').value = item.branch_office.id
        document.getElementById('provider_id_edit').value = item.provider_id
        document.getElementById('category_id_edit').value = item.category_id
        document.getElementById('brand_id_edit').value = item.brand_id
        //console.log(item.provider_id);
        document.getElementById('expiration_edit').value = item.expiration
        document.getElementById('iva_edit').value = item.iva
        document.getElementById('product_key_edit').value = item.product_key
        document.getElementById('unit_product_key_edit').value = item.unit_product_key
        document.getElementById('lot_edit').value = item.lot
        document.getElementById('ieps_edit').value = item.ieps

    }



    function upperCreate(){
        document.getElementById('name').value = document.getElementById('name').value.toUpperCase()
        document.getElementById('name_edit').value = document.getElementById('name_edit').value.toUpperCase()

        return true;
    }
</script>
@endpush
