@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="modal fade" id="authorizationModal" tabindex="-1" aria-labelledby="authorizationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="authorizationForm">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="authorizationModalLabel">Autorización de descuento mayor a 10%</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <input type="hidden" id="user_id" value="{{Auth::user()->rol_id}}">
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" hidden>
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                    Credenciales inválidas
                </div>
                <h5 class="mx-2 mb-2">Credenciales del administrador</h5>
                <div class="form-group m-2">
                    <label for="adminEmail">Correo electrónico</label>
                    <input id="adminEmail" type="email" class="form-control" required/>
                </div>
                <div class="form-group m-2">
                    <label for="adminPassword">Contraseña</label>
                    <input id="adminPassword" type="password" class="form-control" required/>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">CANCELAR</button>
              <button type="submit" class="btn btn-primary">AUTORIZAR</button>
            </div>
          </div>
      </form>
    </div>
</div>
@if(isset($box))
    <div class="modal fade" id="errorPrintModal" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="errorPrintModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="errorPrintModalLabel">Error</h5>
                </div>
                <div class="modal-body">
                    No se pudo imprimir el ticket. Vaya a la lista de ventas para intentarlo de nuevo.
                </div>
                <div class="modal-footer">
                    <button id="reloadButton" type="button" class="btn btn-primary">ACEPTAR</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="productsListModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="productsListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productsListModalLabel">Lista de productos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-success alert-dismissible fade show" id="addedProductAlert" role="alert" hidden>
                    <small id="addedProductName"></small> se ha agregado a la lista de compras
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="accordion" id="accordionExample">
                        @foreach($categories as $category)
                            <div class="card">
                                <div class="card-header" id="heading{{$category->id}}">
                                    <button class="btn btn-block text-left mb-0 category" type="button" data-toggle="collapse" data-target="#collapse{{$category->id}}" data-category="{{$category->id}}" aria-expanded="true" aria-controls="collapse{{$category->id}}">
                                        <h5 class="mb-0 text-primary">{{$category->name}}</h5>
                                    </button>
                                </div>

                                <div id="collapse{{$category->id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="row" id="row{{$category->id}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="">
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
    @if(isset($box))
        <div >
            <div class="row">
                <div class="col-md-10">
                    <h4>Vender</h4>
                </div>
                <div  class="col-md-2 d-flex justify-content-end">
                    <div class="dropdown">
                        <button type="button" class="btn btn-light dropdown-toggle" type="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bell-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                            </svg>
                            <span id="notificationsCount" class="badge badge-primary" hidden>0</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownNotification">
                            <h6 class="dropdown-header">Notificaciones</h6>
                            <div id="notifications" class="dropdown-item">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="row my-4">
                
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" id="bar_code" class="form-control" placeholder="Código de barra"/>
                        <div class="input-group-append">
                            <button id="addProductByBarcodeButton" class="btn btn-outline-secondary">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-upc-scan" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
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
                
                <div class="col-md-2">
                    <div class="col-md-12">
                        <button type="button" id="masVendido" data-toggle="modal" data-target="#masvendidoModal" class="btn btn-primary btn-block">Más Vendido</button>
                    </div>
                </div>


                <!--Modal buscar client-->
                <div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productModalLabel">Buscar cliente</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" id="searchClient" style="text-transform: uppercase" class="form-control" name="searchClient" autocomplate="searchClient" placeholder="Buscar cliente"/>
                                        <div class="input-group-append">
                                            <button id="searchClientButton" class="btn btn-outline-secondary">
                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                                                    <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>  
                                </div>

                                <!--table-->
                                <div class="col-md-12">
                                    <table id="resultTableClient"class="table table-sm table-hover table-responsive-lg overflow-auto my-2">
                                        <thead>
                                            <!--<tr>
                                                <th>Resultado de búsqueda 
                                            </tr>-->
                                            <tr>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Correo</th>
                                            </tr>
                                        </thead>
                                        <tbody id="searchResultClient">
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                <!--Modal mas vendido-->
                <div class="modal fade" id="masvendidoModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productModalLabel">Lo mas vendido de la semana {{sizeof($ventasS)}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!--table-->
                                <div class="col-md-12">
                                    <table class="display table table-striped table-bordered" id="example" style="width:100%">
                                    <!--<table id="resultTableClient" class="table table-sm table-hover table-responsive-lg overflow-auto my-2">-->
                                        <thead>
                                            <tr>
                                                <th scope="col">Producto</th>
                                                <th scope="col">Precio</th>
                                                <th scope="col">Vendido</th>
                                            </tr>
                                        </thead>
                                        <tbody id="masVendidoTable">
                                            @foreach($ventasS as $v)
                                            @if($v->quantity >= 10)
                                            <tr class="item-resultMV" style="cursor: grab;" data-id="{{$v->product_name}}">
                                            <!--<tr >-->
                                                <td>{{$v->product_name}}</td>
                                                <td>${{$v->sale_price}}</td>
                                                <td>{{$v->quantity}}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!--Modal mas vendido-->
                <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productModalLabel">Comentario a la venta</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group  my-3 mx-3">
                                    <label for="name">Descripción</label>
                                    <textarea class="form-control" type="text" name="comentario" id="comentario" value="" placeholder="Agregar algun comentario" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-success" id="agregarComentario">Agregar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-2">
                    <button class="btn btn-secondary btn-block" data-toggle="modal" data-target="#productsListModal">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-grid-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
                        </svg>
                        <small>PRODUCTOS</small>
                    </button>
                </div> -->
                <div class="col-md-12">
                    <table id="resultTable"class="table table-sm table-hover table-responsive-lg overflow-auto my-2" hidden>
                        <thead>
                            <tr>
                                <th>Resultado de búsqueda 
                                    <button id="cleanSearchButton" class="ml-2 btn btn-outline-secondary btn-sm"><small>LIMPIAR</small></button>
                                </th>
                            </tr>
                            <tr>
                                <th scope="col">Código</th>
                                <input type="hidden" id="user" value="{{Auth::user()->rol_id}}">
                                @if (Auth::user()->rol_id==1)
                                     <th scope="col">Sucursal</th>
                                @endif
                               
                                <th scope="col">Nombre</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Precio 1</th>
                                <th scope="col">Precio 2</th>
                                <th scope="col">Precio 3</th>
                            </tr>
                        </thead>
                        <tbody id="searchResult">
                        </tbody>
                    </table>
                </div>
            </div>
            <form id="shoppingListForm" class="needs-validation was-validated" novalidate>
                <div class="row">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive-lg overflow-auto">
                                    <table class="table">
                                        <thead class="bg-secondary thead-light">
                                            <tr>
                                                <th colspan="9">Productos a comprar</th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Código</th>
                                                <th scope="col">Producto</th>
                                                <th scope="col">Marca</th>
                                                <th scope="col">Categoría</th>
                                                <th scope="col">Costo</th>
                                                <th scope="col">Precio</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col">Descuento (%)</th>
                                                <th scope="col">Subtotal</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="shoppingList">         
                                        </tbody>
                                    </table>     
                                </div>    
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="card py-2">
                            <div class="row">
                                <div class="col-md-12 my-2">
                                    <div class="row mx-2">
                                        <div class="col-md-12">
                                            <div class="form-group" style="position: relative">
                                                <label for="additional_discount">Descuento adicional (%)</label>
                                                <input type="number" step="any" min="0" max="100" class="form-control" id="additional_discount"/>
                                                <div class="invalid-tooltip">Descuento inválido</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mx-2">
                                        <div class="col-md-6">
                                            <h6>Subtotal:</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>$ <span id="generalSubtotal">0.00</span></h6>
                                        </div>
                                    </div>
                                    <div class="row mx-2">
                                        <div class="col-md-6">
                                            <h6>Descuento:</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>$ <span id="totalDiscount">0.00</span></h6>
                                        </div>
                                    </div>
                                    <div class="row mx-2">
                                        <div class="col-md-6">
                                            <h6 class="font-weight-bold" >Total a pagar:</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="font-weight-bold" id="total">$ <span id="totalSale">0.00</span> MXN</h6>
                                        </div>
                                    </div>
                                    {{-- <div class="row mx-2">
                                        <div class="col-md-6">
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="font-weight-bold">$ <span id="totalSaleUSD">0.00</span> USD</h6>
                                        </div>
                                    </div> --}}
                                </div>
                              
                                <div class="col-md-12 my-2">
                                    <div class="row mx-2">
                                        <div class="col-md-12" id="type_sale_id" style="display:none">
                                            <div class="form-group">
                                                <label for="payment_type">Tipo de venta </label>
                                                <select class="custom-select" id="sale_type" onchange="selectSale(this.value)">
                                                    <option value="0" selected disabled>Seleciona una opcion</option>
                                                    <option value="1">Traspaso</option>
                                                    <option value="2">Venta</option>
                                                </select>   
                                            </div>
                                        </div>
                                        <div id="sale_trasp" style="display:none">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="payment_type">Sucursales</label>
                                                    <select class="custom-select" id="branch_office">
                                                        @foreach ( $traspacing as $item)
                                                            @if (Auth::user()->branchOffice->id != $item->id )
                                                                <option value="{{$item->id}}">{{$item->name}}</option>   
                                                            @endif
                                                           
                                                        @endforeach
                                                        
                                                     
                                                    </select>   
                                                </div>
                                            </div>
                                        </div>
                                     <div id="sale_public" style="display:none">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="payment_type">Tipo de pago</label>
                                                <select class="custom-select" id="payment_type">
                                                    <option value="0">Efectivo</option>
                                                    <option value="1">Tarjeta</option>
                                                    <option value="2">Crédito</option>
                                                    <option value="3">Pago Mixto</option>
                                                </select>   
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="client">Cliente</label>
                                            <div class="input-group">
                                                <select class="custom-select" name="client_id" id="client_id">
                                                    <option value="">Cliente general</option>
                                                    @foreach($clients as $client)
                                                        <option value="{{$client->id}}">{{$client->name}} {{$client->last_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#clientModal">
                                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                                                            <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-4 mx-2">
                                            <div class="col-md-12">
                                                <button type="button" id="addComment" class="btn btn-primary btn-block" data-toggle="modal" data-target="#commentModal">
                                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                                    </svg>
                                                    <small id="addC">Agregar comentario</small>
                                                    <small id="adderC" hidden>Comentario agregado</small>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12" hidden id="cardIngressDiv">
                                            <div class="form-group">
                                                <label for="ingress">Cantidad en tarjeta</label>
                                                <input type="number" step="any" min="0" class="form-control" id="cardIngress"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="float-right pt-1">
                                                <input hidden type="radio" id="USD" name="USD">
                                            </div>
                                            <div class="float-left pt-1">
                                                <input hidden type="radio" id="MXN" name="MXN" checked>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="ingress">Pago con</label>
                                                <input type="number" step="any" min="0" class="form-control" id="ingress" required/>
                                            </div>
                                   
                                    </div>
                                    <div class="pt-1">
                                        <div class="row mx-2">
                                            <div class="col-md-6">
                                                <h6 class="font-weight-bold">Cambio:</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <div id="turnedDiv">
                                                    <h6 class="font-weight-bold">$ <span id="turned">0.00</span> MXN</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row my-4 mx-2">
                                        <div class="col-md-12">
                                            <button type="button" id="paymentButton" class="btn btn-primary btn-block" disabled>PAGAR</button>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </form> 
        </div>
    @else
        <div class="card p-4 mx-5">
            <div class="row d-flex justify-content-center">
                <h5>Abrir caja</h5>
            </div>
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
            <form action="cashClosing" method="POST">
                @csrf
                <div class="form-group">
                    <label for="branch_office_id">Sucursal</label>
                    <select class="custom-select" id="branch_office_id" name="branch_office_id">
                        @foreach ($branches as $branch)
                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="box_id">Cajad</label>
                    <select class="custom-select" id="box_id" name="box_id" required>
                    </select>
                </div>
                <button id="openBoxButton" type="submit" class="btn btn-primary btn-block mt-3" disabled>ABRIR CAJA</button>
            </form>
        </div>
    @endif
    <form id="reprintForm" target="_blank" action="/reprint" method="post">
        <input id="saleReprintId" type="hidden" name="sale_id">
    </form>
    <form id="reprintFormSend" target="_blank" action="/sendReprint" method="post">
        <input id="sendReprintId" type="hidden" name="transfer_id">
    </form>

</div>
@endsection
@push('scripts')
    <script type="application/javascript">
        $(document).ready(function() {
         //   document.getElementById("search").focus();
            let result = [];
            let generalSubtotal = 0;
            let totalDiscount = 0;
            let totalSale = 0;
            let totalSaleUSD = 0;
            let discountWarning=false;
            let notificationsCount=0;
            const sale_public = document.getElementById('sale_public');
            const type_sale_id = document.getElementById('type_sale_id');
            const user_id = document.getElementById('user_id').value;
           
            // let client= document.getElementById('client');

            $('#payment_type').change( function() {
                $('#cardIngressDiv').prop('hidden', true);
                $('#cardIngress').val(0);
                if ($('#payment_type').val() == 1) {
                    $('#ingress').prop('readonly', true);
                    $('#ingress').val(totalSale);
                    // client.style.visibility = 'hidden';
                    update();
                }else if ($('#payment_type').val() == 0) {
                    $('#ingress').prop('readonly', false);
                    // client.style.visibility = 'hidden';
                }else if($('#payment_type').val() == 3){
                    $('#ingress').prop('readonly', false);
                    $('#cardIngressDiv').prop('hidden', false);
                    $('#cardIngress').prop('required', true);
                    $('#ingress').val(1);
                }else{
                    $('#ingress').prop('readonly', true);
                    $('#ingress').val(0);
                    if($( "#client_id option:selected" ).val()==""){
                        console.log($( "#client_id option:selected" ).val());
                        $('#paymentButton').prop('disabled',true);
                    }else{
                        console.log($( "#client_id option:selected" ).val());
                        $('#paymentButton').prop('disabled',false);
                    }
                    // client.style.visibility = 'visible';
                }   
            });
            $('#client_id').change( function() {
                //console.log($( "#client_id option:selected" ).val());
                //$( "#client_id option:selected" ).val(100);
                //console.log($( "#client_id option:selected" ).val());
                    if($( "#client_id option:selected" ).val()==""){
                        console.log($( "#client_id option:selected" ).val());
                        $('#paymentButton').prop('disabled',true);
                        
                    }else{
                        console.log($( "#client_id option:selected" ).val());
                        $('#paymentButton').prop('disabled',false);
                    }
            });
            $('#USD').change(function (){
                if (document.getElementById("USD").checked == false) {
                    document.getElementById("MNX").checked = false;
                    document.getElementById("USD").checked = true;
                    update();
                } else {
                    if (document.getElementById("MXN").checked == true) {
                        document.getElementById("USD").checked = true;
                        document.getElementById("MXN").checked = false;
                    }
                    update();
                }
            });

            $('#MXN').change(function (){
                if (document.getElementById("MXN").checked == false) {
                    document.getElementById("USD").checked = false;
                    document.getElementById("MXN").checked = true;
                    update();
                } else {
                    if (document.getElementById("USD").checked == true) {
                        document.getElementById("MXN").checked = true;
                        document.getElementById("USD").checked = false;
                    }
                    update();
                }
            });

            if($('#branch_office_id').val()!==undefined){
                console.log('holaaaaa');
                getBoxes();
            }
            if(user_id ==1 || user_id==3)
            {
                type_sale_id.style.display= "inline-block";
            }
            else{
                sale_public.style.display= "inline-block";
            }

            function fsearchClient(){
                $('#searchResultClient').empty();
                var usuario = document.getElementById('user').value;
                if($("#searchClient").val().length!=0){
                    $.ajax({
                        url: "/searchClient",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'GET',
                        contentType: "application/json; charset=iso-8859-1",
                        data: {'search':$('#searchClient').val().toUpperCase()},
                        dataType: 'html',
                        success: function(data) {
                            result=JSON.parse(data);
                            if(result.length!==0){
                                result.forEach(function(element,index){
                                    $('#searchResultClient').append(
                                        '<tr class="item-resultC" style="cursor: grab;" data-id="'+element.id+'">'+
                                            '<td>'+element.name+' '+ element.last_name+'</td>'+
                                            '<td>'+element.email+'</td>'+
                                        '</tr>'
                                    );
                                });
                                $('.item-resultC').off();
                                $('.item-resultC').click(function() {
                                    addClient($(this).data('id'));
                                    $('#clientModal').modal('hide');
                                });
                            }   
                            else{
                                $('#searchResultClient').append(
                                    '<tr class="item-result">'+
                                        '<td colspan="8">No se encontraron resultados</td>'+
                                    '</tr>'
                                );
                            } 
                        },
                        error: function(e) {
                            console.log("ERROR", e);
                        },
                    });
                }
            }

            $('.item-resultMV').click(function() {
                $.ajax({
                    url: "/search",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    contentType: "application/json; charset=iso-8859-1",
                    data: {'search':$(this).data('id')},
                    dataType: 'html',
                    success: function(data) {
                        result=JSON.parse(data);
                        if(result.length!==0){
                            //console.log("R: "+result[0].id);
                            $('#masvendidoModal').modal('hide');
                            addProduct(result[0].id);
                        }else{
                            alert("No se encontraron resultados!");
                        } 
                    },
                    error: function(e) {
                        console.log("ERROR", e);
                    },
                });
            });

            function search(){
                $('#searchResult').empty();
                var usuario = document.getElementById('user').value;
                if($("#search").val().length!=0){
                    $.ajax({
                        url: "/search",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'GET',
                        contentType: "application/json; charset=iso-8859-1",
                        data: {'search':$('#search').val().toUpperCase()},
                        dataType: 'html',
                        success: function(data) {
                            result=JSON.parse(data);
                            $('#resultTable').prop('hidden',false);
                            if(result.length!==0){
                                result.forEach(function(element,index){
                                    if (element.brand_name == undefined) {
                                        element.brand_name = 'Ninguna';
                                    }
                                    if(usuario == 1){
                                     $('#searchResult').append(
                                        '<tr class="item-result" style="cursor: grab;" data-id="'+element.id+'">'+
                                            '<td>'+element.bar_code+'</td>'+
                                            '<td>'+element.office_name+'</td>'+
                                            '<td>'+element.name+'</td>'+
                                            '<td>'+element.brand_name+'</td>'+
                                            '<td>'+element.category_name+'</td>'+
                                            '<td>'+element.category_name+'</td>'+
                                            '<td>'+element.stock+'</td>'+
                                            '<td>'+element.price_1+'</td>'+
                                            '<td>'+element.price_2+'</td>'+
                                            '<td>'+element.price_3+'</td>'+
                                        '</tr>'
                                    );
                                    }
                                    else {
                                        $('#searchResult').append(
                                        '<tr class="item-result" style="cursor: grab;" data-id="'+element.id+'">'+
                                            '<td>'+element.bar_code+'</td>'+
                                            '<td>'+element.name+'</td>'+
                                            '<td>'+element.brand_name+'</td>'+
                                            '<td>'+element.category_name+'</td>'+
                                            '<td>'+element.stock+'</td>'+
                                            '<td>'+element.price_1+'</td>'+
                                            '<td>'+element.price_2+'</td>'+
                                            '<td>'+element.price_3+'</td>'+
                                        '</tr>'
                                    );
                                    }
                                   
                                });
                                $('.item-result').off();
                                $('.item-result').click(function() {
                                    $('#resultTable').prop('hidden',true);
                                    addProduct($(this).data('id'));
                                });
                            }   
                            else{
                                $('#searchResult').append(
                                    '<tr class="item-result">'+
                                        '<td colspan="8">No se encontraron resultados</td>'+
                                    '</tr>'
                                );
                            } 
                        },
                        error: function(e) {
                            console.log("ERROR", e);
                        },
                    });
                }
            }

            function addClient(idClient){
                let client = result.find(element => element.id == idClient);
                //console.log("Cliente: "+client.name);
                //$("#client_id").val(client.name);
                $('#searchClient').val('');
                $("#client_id option[value="+ client.id +"]").attr("selected",true);
                
            }

            function addProduct(idProduct){
                let product = result.find(element => element.id == idProduct)
                $('#addedProductName').text(product.name);
                let exist = false;
                $('#shoppingList').children().each(function(){
                    if($(this).data('id')===idProduct){
                        exist = true;
                        return true;
                    }
                });
                $('#search').val('');
                $('#searchResult').empty();
                if(exist){
                    let quantity = $('#product'+product.id).find('.quantity').val();
                    quantity++;
                    $('#product'+product.id).find('.quantity').val(quantity);
                }
                else{
                    let stringSelectOptions = "";
                    let stringStockWarning = "";
                    if(product.price_2!=null){
                        stringSelectOptions+='<option value="'+product.price_2+'">$ '+product.price_2+'</option>';
                    }
                    if(product.price_3!=null){
                        stringSelectOptions+='<option value="'+product.price_3+'">$ '+product.price_3+'</option>';
                    }
                    if(product.brand_name == undefined){
                        product.brand_name = 'Ninguna'
                    }
                    $('#shoppingList').append(
                        '<tr id="product'+product.id+'" data-id="'+product.id+'">'+
                            '<td>'+product.bar_code+'</td>'+
                            '<td class="name">'+product.name+'</td>'+
                            '<td class="brand-name">'+product.brand_name+'</td>'+
                            '<td>'+product.category_name+'</td>'+
                            '<td class="costo">'+product.costo+'</td>'+
                            '<td>'+
                                '<select class="custom-select price" style="width:150px;">'+
                                    '<option value="'+product.price_1+'">$ '+product.price_1+'</option>'+
                                    stringSelectOptions+
                                '</select>'+
                            '</td>'+
                            '<td>'+
                                '<div class="form-group" style="position: relative ">'+
                                    '<input type="number" class="quantity observable form-control" style="width:150px;" value="1" min="1" max="'+product.stock+'"" required/>'+
                                    '<div class="invalid-tooltip quantity-tooltip"></div>'+
                                '</div>'+
                            ' </td>'+
                            '<td>'+
                                '<div class="form-group" style="position: relative ">'+
                                    '<input type="number" class="discount observable form-control" style="width:150px;" value="0" min="0" max="100" required/>'+
                                    '<div class="invalid-tooltip discount-tooltip"></div>'+
                                '</div>'+
                            '</td>'+
                            '<td>$ <span class="subtotal">'+product.price+'</span></td>'+
                            '<td>'+
                                '<button type="button" data-id="'+product.id+'" class="btn btn-outline-danger btn-sm btn-delete"><small>QUITAR</small></button>'+
                            '</td>'+
                        '</tr>'
                    );
                    
                    $('.observable').off();
                    $('.price').off();
                    
                    $('.price').change(function(){
                        update(); 
                    });
                    $('.observable').change(function(){
                        update(); 
                    });
                    $('.observable').keyup(function(){
                        update(); 
                    });
                }
                $('.btn-delete').click(function(){
                    $('#product'+$(this).data('id')).remove(); 
                    update(); 
                });
                update(); 
            }
            function searchByBarcode(itemButton){
                $('#searchResult').empty();
                if($("#bar_code").val().length!=0){
                    $.ajax({
                        url: "/searchByCode",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'GET',
                        contentType: "application/json; charset=iso-8859-1",
                        data: {'search':$('#bar_code').val()},
                        dataType: 'html',
                        success: function(data) {
                            result=JSON.parse(data);
                            if(result.length!==0){
                                addProduct(result[0].id)
                            }
                            else{
                                $('#resultTable').prop('hidden',false);
                                $('#searchResult').append(
                                    '<tr class="item-result">'+
                                        '<td colspan="8">No se encontraron resultados</td>'+
                                    '</tr>'
                                );
                            }
                        },
                        error: function(e) {
                            console.log("ERROR", e);
                        },
                    });
                }
                $("#bar_code").val('');
            }
            function update(){
                let shoppingListForm = document.getElementById('shoppingListForm');
                generalSubtotal=0;
                totalDiscount=0;
                totalSale=0;
                totalSaleUSD=0;
                discountWarning = false;
                notificationsCount=0
                $('.stock-warning').remove();
                $('.additional_discount-warning').remove();
                $('#shoppingList').children().each(function (){
                    let price = parseFloat($(this).find(':selected').val());
                    let subtotal = parseFloat($(this).find('.subtotal').text());
                    let quantity = $(this).find('.quantity').val();
                    let discount = $(this).find('.discount').val();
                    let temporalStock=parseInt($(this).find('.quantity').prop('max'))-quantity;
                    if(temporalStock<6){
                        addNotification('stock-warning',$(this).find('.name').text()+' | '+$(this).find('.brand-name').text()+' tiene '+temporalStock+' productos en stock')
                    }
                    if(quantity<1 || quantity===''){
                        $(this).find('.quantity-tooltip').text('Cantidad inválida.');
                    }
                    else if(quantity>$(this).find('.quantity').attr('max')){
                        $(this).find('.quantity-tooltip').text('La cantidad supera el stock.');
                    }
                    if(discount<0||discount>99 || quantity===''){
                        $(this).find('.discount-tooltip').text('Descuento inválido.');
                    }    
                    generalSubtotal+=quantity*price;
                    generalSubtotal= ((Math.round( (generalSubtotal) * 10000) / 10000));
                    if(!parseInt($('#additional_discount').val())>0){
                        totalDiscount+=quantity*(price*(discount/100));
                        totalDiscount = ((Math.round( (totalDiscount) * 10000) / 10000));
                        price = price - (price*(discount/100));
                        if (discount>10){
                            discountWarning = true;
                        }
                    }
                   
                    subtotal=price*quantity;
                    subtotal=((Math.round( (subtotal) * 10000) / 10000));
                    totalSale+=subtotal;
                    totalSaleUSD+=subtotal;//20;
                    
                    $(this).find('.subtotal').text(subtotal);
                });
                if(!parseInt($('#additional_discount').val())>0){
                    $('.discount').each(function (){
                        $(this).prop('readonly',false);
                    });
                }
                else{
                    if($('#additional_discount').val()>0 ){
                        let discount = $('#additional_discount').val();
                        totalDiscount=generalSubtotal*(discount/100);
                        totalDiscount = ((Math.round( (totalDiscount) * 10000) / 10000));
                        totalSale = generalSubtotal - (generalSubtotal*(discount/100));
                        totalSale = ((Math.round( (totalSale) * 10000) / 10000));
                        totalSaleUSD = totalSale;//20;
                        if (discount>10){
                            discountWarning = true;
                        }
                        $('.discount').each(function (){
                        $(this).prop('readonly',true);
                    });
                    addNotification('additional_discount-warning','Los descuentos sobre el total anulan los decuentos por producto');
                    }
                }
                $('#totalDiscount').text(totalDiscount.toFixed(2));
                $('#generalSubtotal').text(generalSubtotal.toFixed(2));
                $('#totalSale').text(totalSale.toFixed(2));
                $('#totalSaleUSD').text(totalSaleUSD.toFixed(2));
                // if ($('#payment_type').val() == 1) {
                //     $('#ingress').val(totalSale.toFixed(2));    
                // }else{
                //     $('#ingress').text(totalSale.toFixed(2));    
                // }
                if (document.getElementById("USD").checked == true) {
                    let turned =  $('#ingress').val() - (totalSale.toFixed(2)*0.050);
                    turned = ((Math.round( (turned) * 10000) / 10000));
                    $('#ingress').prop('min',totalSale.toFixed(2)*0.050);
                    /*if(turned>0){
                        //ACA CONVERTIR TOTAL A DLS Y REGRESAR CAMBIO EN MXN
                        $('#turned').text(turned.toFixed(2)*20);
                    }
                    else{
                        $('#turned').text('0.00');
                    }*/
                } else {
                    //Aqui hacer la el total de la suma de cardIngress+ parseFloat($('#cardIngress').val()) ) - totalSale.toFixed(2);
                    let turned =  (parseFloat($('#ingress').val()) + parseFloat($('#cardIngress').val())) - totalSale.toFixed(2);
                    turned = ((Math.round( (turned) * 10000) / 10000));
                    if ($('#payment_type').val() == 2 || $('#payment_type').val() == 3) {
                        //$('#ingress').prop('min',1);
                        $('#ingress').prop('min', 1);
                        $('#cardIngress').prop('max', totalSale.toFixed(2)-1);
                    }else{
                        $('#ingress').prop('min',totalSale.toFixed(2));
                    }
                    /*if(turned>0){
                        //ACA CONVERTIR TOTAL A DLS Y REGRESAR CAMBIO EN MXN
                        $('#turned').text(turned.toFixed(2));
                    }
                    else{
                        $('#turned').text('0.00');
                    }*/
                }
                
                if(shoppingListForm.checkValidity() && totalSale!==0){
                    $('#paymentButton').prop('disabled',false);
                }
                else{
                    $('#paymentButton').prop('disabled',true);
                }

                if($('#ingress').val() == 0){
                    $('#paymentButton').prop('disabled',true);
                }else{
                    $('#paymentButton').prop('disabled',false);
                }
                $('#paymentButton').off();
                $('.discount-warning').remove();
                if(discountWarning){ 
                    addNotification('discount-warning','Los descuentos mayores a 10% necesitan autorización del gerente');
                    $('#paymentButton').attr('data-toggle','modal');
                    $('#paymentButton').attr('data-target','#authorizationModal');
                    
                }
                else{
                    $('#paymentButton').click(function(){
                        pay();
                    }); 
                    $('#paymentButton').removeAttr('data-toggle','modal');
                    $('#paymentButton').removeAttr('data-target','#authorizationModal');
                }
                $('#notificationsCount').text(notificationsCount);
                if(notificationsCount==0){
                    $('#notificationsCount').prop('hidden',true);
                }
                else{
                    $('#notificationsCount').prop('hidden',false);
                }
                
            }
            function addNotification(notificationClass,content){
                notificationsCount++;
                $('#notifications').append(
                    '<div class="alert alert-warning '+notificationClass+'" role="alert">'+
                        '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
                            '<path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>'+
                        '</svg>'+
                        content+
                    '</div>'
                );
            }
            function encode_utf8(s) {
            return unescape(encodeURIComponent(s));
        }
            function pay(){
                $('input,select').each(function(){
                    $(this).prop('readonly',true);
                });
                $('#authorizationModal').modal('hide');
                $('#paymentButton').prop('disabled',true);
                let items = [];
                $('#shoppingList').children().each(function (){
                    let price = parseFloat($(this).find(':selected').val());
                    let total = parseFloat($(this).find('.subtotal').text());
                    let quantity = parseFloat($(this).find('.quantity').val());
                    let discount = parseFloat($(this).find('.discount').val());
                    let costo =  parseFloat($(this).find('.costo').text());
                    let subtotal = price * quantity;
                    items.push({
                        id : $(this).data('id'),
                        quantity : quantity,
                        discount : discount,
                        sale_price : price,
                        total : total,
                        subtotal : subtotal,
                        costo:costo
                    });
                });
                let commen = "";
                if($('#comentario').val() != 0){
                    console.log("Comantario agregado");
                    commen = $('#comentario').val();
                }else{
                    console.log("Sin comentarios");
                }
                let request = {
                    sale : {
                        payment_type: $('#payment_type').find(':selected').val(),
                        amount_discount: totalDiscount,
                        discount: parseInt($('#additional_discount').val()),
                        cart_subtotal: generalSubtotal,
                        cart_total: totalSale,
                        turned: parseInt($('#turned').text()),
                        ingress: parseInt($('#ingress').val()),
                        card_ingress: parseInt($('#cardIngress').val()),
                        client_id: $('#client_id').find(':selected').val(),
                    },
                    products:items,
                    sale_type:{
                        saletype: $('#sale_type').find(':selected').val(),
                        branch_office: $('#branch_office').find(':selected').val(),
                        comentario: commen,
                    }
                    };
                    console.log("req: "+request);
                $.ajax({
                    url: "/sale",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    contentType: "application/json; charset=iso-8859-1",
                    data:JSON.stringify(request),
                    dataType: 'html',
                    success: function(data) {                                                
                        if(JSON.parse(data).success){
                            //console.log(JSON.parse(data).data.products_in_sale)
                            console.log('success')
                                if(JSON.parse(data).data.saletype == 1)
                                { 
                                   console.log(JSON.parse(data).transfer.id);
                                    $('#sendReprintId').val(JSON.parse(data).transfer.id)
                                    $('#reprintFormSend').submit()
                                    location.reload();  
                                   
                                //  reprintFormSend sendReprintId
                                }
                                else
                                {
                                    console.log(JSON.parse(data));
                                   $('#saleReprintId').val(JSON.parse(data).data.id)
                                    $('#reprintForm').submit()
                                    location.reload();
                                }
                                
                                
                     }   
                        else{
                            alert(data);
                            $('#paymentButton').prop('disabled',false);
                            console.log(JSON.parse(data));
                        } 
                    },
                    error: function(e) {
                        console.log("ERROR", e);
                    },
                });
            }
            function getBoxes(){
                $('#box_id').empty();
               
                $.ajax({
                    url: "/getBox/"+$('#branch_office_id').val(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    contentType: "application/json; charset=iso-8859-1",
                    data: null,
                    dataType: 'html',
                    success: function(data) {
                        let boxes=JSON.parse(data);
                        if(boxes.length!==0){
                       
                            $('#openBoxButton').prop('disabled',false);
                        }
                        boxes.forEach((box)=>{
                            $('#box_id').append(
                                $('<option></option>').val(box.id).text(box.number)
                            );
                        });
                    },
                    error: function(e) {
                        console.log("ERROR", e);
                    },
                });
            }
            $('#searchButton').click( function() {
                search();
            });
            $('#searchClientButton').click( function() {
                fsearchClient();
            });
            $('#search').keyup( function(event) {
                if(event.keyCode===13){
                    if($(this).val().length!==0){
                        search();
                    }
                    else if($(this).val().length===0){
                        $('#searchResult').empty();
                    }
                }
            }); 
            $('#searchClient').keyup( function(event) {
                if(event.keyCode===13){
                    if($(this).val().length!==0){
                        fsearchClient();
                    }
                    else if($(this).val().length===0){
                        $('#searchResultClient').empty();
                    }
                }
            }); 
            $('#cleanSearchButton').click(function(){
                $('#search').val('');
                $('searchResult').empty();
                $('#resultTable').prop('hidden',true);
            });
            $('#addProductByBarcodeButton').click( function() {
                searchByBarcode();
            });
            $('#agregarComentario').click(function(){
                if($('#comentario').val() != 0){
                    $('#addC').prop('hidden', true);
                    $('#adderC').prop('hidden', false);
                }
                $('#commentModal').modal('hide');
            });
            $('#bar_code').keyup( function(event) {
                if(event.keyCode===13){
                    if($(this).val().length!==0){
                        searchByBarcode();
                    }
                }
            }); 
            $('#cardIngress').keyup( function(event) {
                update();
            });
            $('#ingress').keyup( function(event) {
                update();
            });
            $( '#authorizationForm' ).submit(function( event ) {
                event.preventDefault();
                let credentials = {
                    'email':$('#adminEmail').val(),
                    'password':$('#adminPassword').val(),
                };
                $.ajax({
                    url: "/validatePromotion",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    contentType: "application/json; charset=iso-8859-1",
                    data:JSON.stringify(credentials),
                    dataType: 'html',
                    success: function(data) {
                        if(JSON.parse(data).success){
                            $('.alert-warning').prop('hidden',true)
                            pay();
                        }   
                        else{
                            $('.alert-warning').prop('hidden',false)
                        } 
                    },
                    error: function(e) {
                        console.log("ERROR", e);
                    },
                });
            });
            $('#additional_discount').keyup(function(){
                update();
            })
            $('#branch_office_id').change(function (){
                getBoxes();
            });
            $('.category').click( function() {
                let idCategory = $(this).data('category');
                let url = "/sale/productsCategory/" +  idCategory;
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    contentType: "application/json; charset=iso-8859-1",
                    data: null,
                    dataType: 'html',
                    success: function(data) {
                        result=JSON.parse(data);
                        let row = '#row'+idCategory;
                        $(row).empty();
                        if(result.length!==0){   
                            result.forEach(product => {
                                $(row).append(
                                '  <div class="col-md-2">'+
                                    '<div class="card product my-3" style="cursor: grab; min-height:220px;" data-id="'+product.id+'">'+
                                        '<img style="width: 100%;height: 125px;object-fit: cover;" src="'+product.image+'" class="card-img-top">'+
                                        '<div class="card-body">'+
                                        ' <h6 class="card-title">'+product.name+'</h6>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'
                                );
                            });
                            $('.product').off();
                            $('.product').click(function (){
                                addProduct($(this).data('id'));
                                $('#addedProductAlert').prop('hidden',false);
                                setTimeout(() => {
                                    $('#addedProductAlert').prop('hidden',true);
                                }, 2500);
                            })
                        }   
                        else{
                            $(row).append(
                            '<div class="col-md-12 d-flex justify-content-center">'+
                                '<div class="my-1">'+
                                    '<p>Aún no hay productos en esta categoría</p>'+
                                '</div>'+
                            '</div>'
                            );
                        } 
                    },
                    error: function(e) {
                        console.log("ERROR", e);
                    },
                });
            });
            $('#reloadButton').click(function (){
                location.reload();
            });
        }); 
      const  selectSale = (valor) =>
        {
            const sale_trasp = document.getElementById('sale_trasp');
            const sale_public = document.getElementById('sale_public');
            const paymentButton = document.getElementById('paymentButton');
           
           
          if(valor == 1)
          {
                    sale_public.style.display = 'none';
                    sale_trasp.style.display= "inline-block";
                    paymentButton.innerText = 'Enviar';
                    paymentButton.disabled = false;
          
          }
          if(valor == 2)
          {
            sale_public.style.display = "inline-block";
            sale_trasp.style.display= 'none';
            paymentButton.innerText = 'Pagar';
            
          }
        }
    </script>
@endpush