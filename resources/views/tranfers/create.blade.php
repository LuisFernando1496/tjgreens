@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                <small id="addedProductName"></small> se ha agregado a la lista de transferibles
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
    <div>
        <div class="row">
            <div class="col-md-10">
                <h4>Traspasos</h4>
            </div>
            <div class="col-md-2 d-flex justify-content-end">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle" type="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bell-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z" />
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
                    <input type="text" id="bar_code" class="form-control" placeholder="Código de barra" />
                    <div class="input-group-append">
                        <button id="addProductByBarcodeButton" class="btn btn-outline-secondary">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-upc-scan" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5z" />
                                <path d="M3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="search" style="text-transform: uppercase" class="form-control" name="search" autocomplate="search" placeholder="Buscar producto" />
                    <div class="input-group-append">
                        <button id="searchButton" class="btn btn-outline-secondary">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z" />
                                <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-secondary btn-block" data-toggle="modal" data-target="#productsListModal">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-grid-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z" />
                    </svg>
                    <small>PRODUCTOS</small>
                </button>
            </div>
            <div class="col-md-12">
                <table id="resultTable" class="table table-sm table-hover table-responsive-lg overflow-auto my-2" hidden>
                    <thead>
                        <tr>
                            <th>Resultado de búsqueda
                                <button id="cleanSearchButton" class="ml-2 btn btn-outline-secondary btn-sm"><small>LIMPIAR</small></button>
                            </th>
                        </tr>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Existencia</th>
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
                                            <th colspan="9">Productos a traspasar</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Código</th>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Marca</th>
                                            <th scope="col">Categoría</th>
                                            <th scope="col">Existencia</th>
                                            <th scope="col">Cantidad</th>
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
                                <div class="col-md-12">
                                    <label for="destination_branch_office_id">Sucursal a transferir</label>
                                    <select class="custom-select" id="destination_branch_office_id" name="destination_branch_office_id" required>
                                        @foreach ($branches as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12" style="padding-top: 5%;">
                                    <label for="details">Detalles</label>
                                    <textarea class="form-group" style="border-color: green;" name="details" id="details" required></textarea>
                                </div>
                                <div class="row my-4 mx-2">
                                    <div class="col-md-12">
                                        <button type="button" id="transferButton" class="btn btn-primary btn-block" disabled>Transferir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script type="application/javascript">
    $(document).ready(function() {
        let result = [];
        let notificationsCount = 0;

        $('#transferButton').click(function() {
            pay();
        });

        function search() {
            $('#searchResult').empty();
            if ($("#search").val().length != 0) {
                $.ajax({
                    url: "/search",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    contentType: "application/json; charset=iso-8859-1",
                    data: {
                        'search': $('#search').val().toUpperCase()
                    },
                    dataType: 'html',
                    success: function(data) {
                        result = JSON.parse(data);
                        $('#resultTable').prop('hidden', false);
                        if (result.length !== 0) {
                            result.forEach(function(element, index) {
                                if (element.brand_name == undefined) {
                                    element.brand_name = 'Ninguna';
                                }
                                $('#searchResult').append(
                                    '<tr class="item-result" style="cursor: grab;" data-id="' + element.id + '">' +
                                    '<td>' + element.bar_code + '</td>' +
                                    '<td>' + element.name + '</td>' +
                                    '<td>' + element.brand_name + '</td>' +
                                    '<td>' + element.category_name + '</td>' +
                                    '<td>' + element.stock + '</td>' +
                                    '</tr>'
                                );
                            });
                            $('.item-result').off();
                            $('.item-result').click(function() {
                                $('#resultTable').prop('hidden', true);
                                addProduct($(this).data('id'));
                            });
                        } else {
                            $('#searchResult').append(
                                '<tr class="item-result">' +
                                '<td colspan="8">No se encontraron resultados</td>' +
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

        function addProduct(idProduct) {
            let product = result.find(element => element.id == idProduct)
            $('#addedProductName').text(product.name);
            let exist = false;
            $('#shoppingList').children().each(function() {
                if ($(this).data('id') === idProduct) {
                    exist = true;
                    return true;
                }
            });
            $('#search').val('');
            $('#searchResult').empty();
            if (exist) {
                let quantity = $('#product' + product.id).find('.quantity').val();
                quantity++;
                $('#product' + product.id).find('.quantity').val(quantity);
            } else {
                if (product.brand_name == undefined) {
                    product.brand_name = 'Ninguna'
                }
                $('#shoppingList').append(
                    '<tr id="product' + product.id + '" data-id="' + product.id + '">' +
                    '<td class="bar_code">' + product.bar_code + '</td>' +
                    '<td class="name">' + product.name + '</td>' +
                    '<td class="brand-name">' + product.brand_name + '</td>' +
                    '<td>' + product.category_name + '</td>' +
                    '<td>' +
                    '<div class="form-group" style="position: relative ">' +
                    '<input type="number" class="form-control" style="width:150px;" value="' + product.stock + '"" readonly />' +
                    '</div>' +
                    '</td>' +
                    '<td>' +
                    '<div class="form-group" style="position: relative ">' +
                    '<input type="number" class="quantity observable form-control" style="width:150px;" value="1" min="1" max="' + product.stock + '"" required/>' +
                    '<div class="invalid-tooltip quantity-tooltip"></div>' +
                    '</div>' +
                    ' </td>' +
                    '<td>' +
                    '<button type="button" data-id="' + product.id + '" class="btn btn-outline-danger btn-sm btn-delete"><small>QUITAR</small></button>' +
                    '</td>' +
                    '</tr>'
                );

                $('.observable').off();
                $('.observable').change(function() {
                    update();
                });
                $('.observable').keyup(function() {
                    update();
                });
            }
            $('.btn-delete').click(function() {
                $('#product' + $(this).data('id')).remove();
                update();
            });
            update();
        }

        function searchByBarcode(itemButton) {
            $('#searchResult').empty();
            if ($("#bar_code").val().length != 0) {
                $.ajax({
                    url: "/searchByCode",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    contentType: "application/json; charset=iso-8859-1",
                    data: {
                        'search': $('#bar_code').val()
                    },
                    dataType: 'html',
                    success: function(data) {
                        result = JSON.parse(data);
                        if (result.length !== 0) {
                            addProduct(result[0].id)
                        } else {
                            $('#resultTable').prop('hidden', false);
                            $('#searchResult').append(
                                '<tr class="item-result">' +
                                '<td colspan="8">No se encontraron resultados</td>' +
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

        function update() {
            let shoppingListForm = document.getElementById('shoppingListForm');
            notificationsCount = 0
            $('.stock-warning').remove();
            $('#shoppingList').children().each(function() {
                let quantity = $(this).find('.quantity').val();
                let temporalStock = parseInt($(this).find('.quantity').prop('max')) - quantity;
                if (temporalStock < 6) {
                    addNotification('stock-warning', $(this).find('.name').text() + ' | ' + $(this).find('.brand-name').text() + ' tiene ' + temporalStock + ' productos en stock')
                }
                if (quantity < 1 || quantity === '') {
                    $(this).find('.quantity-tooltip').text('Cantidad inválida.');
                } else if (quantity > $(this).find('.quantity').attr('max')) {
                    $(this).find('.quantity-tooltip').text('La cantidad supera el stock.');
                }
            });

            if (shoppingListForm.checkValidity()) {
                $('#transferButton').prop('disabled', false);
            } else {
                $('#transferButton').prop('disabled', true);
            }
            // $('#transferButton').off();
            $('#notificationsCount').text(notificationsCount);
            if (notificationsCount == 0) {
                $('#notificationsCount').prop('hidden', true);
            } else {
                $('#notificationsCount').prop('hidden', false);
            }

        }

        function addNotification(notificationClass, content) {
            notificationsCount++;
            $('#notifications').append(
                '<div class="alert alert-warning ' + notificationClass + '" role="alert">' +
                '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">' +
                '<path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>' +
                '</svg>' +
                content +
                '</div>'
            );
        }

        function encode_utf8(s) {
            return unescape(encodeURIComponent(s));
        }

        function pay() {
            $('input,select').each(function() {
                $(this).prop('readonly', true);
            });
            $('#transferButton').prop('disabled', true);
            let items = [];
            $('#shoppingList').children().each(function() {
                // console.log("KKK "+$(this).find("td:eq(0)").html());
                let quantity = parseFloat($(this).find('.quantity').val());
                let barcode = $(this).find("td:eq(0)").html();

                items.push({
                    product_id: $(this).data('id'),
                    quantity: quantity,
                    barcode: barcode,
                });
            });
            let request = {
                transfer: {
                    details: $('#details').val(),
                    destination_branch_office_id: $('#destination_branch_office_id').find(':selected').val(),
                    status: 'Enviado'
                },
                products: items
            };

            $.ajax({
                url: "/transfers",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                contentType: "application/json; charset=iso-8859-1",
                data: JSON.stringify(request),
                dataType: 'html',
                success: function(data) {
                    console.log(data)
                    if (JSON.parse(data).success) {
                        alert(JSON.parse(data).good);
                        console.log(JSON.parse(data).good)
                        location.reload();
                    } else {
                        // console.log(data.error);
                        alert(JSON.parse(data).error);
                        $('#transferButton').prop('disabled', false);
                        console.log(JSON.parse(data).error);
                        location.reload();
                    }
                },
                error: function(e) {
                    console.log("ERROR", e);
                },
            });
        }
        $('#searchButton').click(function() {
            search();
        });
        $('#search').keyup(function(event) {
            if (event.keyCode === 13) {
                if ($(this).val().length !== 0) {
                    search();
                } else if ($(this).val().length === 0) {
                    $('#searchResult').empty();
                }
            }
        });
        $('#cleanSearchButton').click(function() {
            $('#search').val('');
            $('searchResult').empty();
            $('#resultTable').prop('hidden', true);
        });
        $('#addProductByBarcodeButton').click(function() {
            searchByBarcode();
        });
        $('#bar_code').keyup(function(event) {
            if (event.keyCode === 13) {
                if ($(this).val().length !== 0) {
                    searchByBarcode();
                }
            }
        });
        $('.category').click(function() {
            let idCategory = $(this).data('category');
            let url = "/sale/productsCategory/" + idCategory;
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
                    result = JSON.parse(data);
                    let row = '#row' + idCategory;
                    $(row).empty();
                    if (result.length !== 0) {
                        result.forEach(product => {
                            $(row).append(
                                '  <div class="col-md-2">' +
                                '<div class="card product my-3" style="cursor: grab; min-height:220px;" data-id="' + product.id + '">' +
                                '<img style="width: 100%;height: 125px;object-fit: cover;" src="' + product.image + '" class="card-img-top">' +
                                '<div class="card-body">' +
                                ' <h6 class="card-title">' + product.name + '</h6>' +
                                '</div>' +
                                '</div>' +
                                '</div>'
                            );
                        });
                        $('.product').off();
                        $('.product').click(function() {
                            addProduct($(this).data('id'));
                            $('#addedProductAlert').prop('hidden', false);
                            setTimeout(() => {
                                $('#addedProductAlert').prop('hidden', true);
                            }, 2500);
                        })
                    } else {
                        $(row).append(
                            '<div class="col-md-12 d-flex justify-content-center">' +
                            '<div class="my-1">' +
                            '<p>Aún no hay productos en esta categoría</p>' +
                            '</div>' +
                            '</div>'
                        );
                    }
                },
                error: function(e) {
                    console.log("ERROR", e);
                },
            });
        });
    });
</script>
@endpush