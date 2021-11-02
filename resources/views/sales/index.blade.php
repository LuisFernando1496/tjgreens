@extends('layouts.app')

@section('content')
<div class="container">
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
    <div class="modal fade" id="openCashBoxModal" tabindex="-1" aria-labelledby="openCashBoxModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="openCashBoxModalLabel">Cerrar caja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="closeBox/{{$box->id}}" target="_blank" method="POST" onsubmit="closeModal()">
                    @csrf
                    <div class="modal-body">
                        ¿Está seguro de cerrar su caja?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn btn-primary">CERRAR CAJA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div style="text-align:right">
        <button id="closeBoxButton" type="button" class="btn btn-outline-secondary btn-sm my-2" data-toggle="modal" data-target="#openCashBoxModal">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cart-dash-fill mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM4 14a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm7 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM6.5 7a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4z" />
            </svg>
            <small>CERRAR CAJA</small>
        </button>
    </div>
    <table class="display table table-striped table-bordered" id="example" style="width:100%">
        <thead class="black white-text">
            <tr>
                <th scope="col">Folio general</th>
                <th scope="col">Folio sucursal</th>
                <th scope="col">Empleado</th>
                {{-- solo si es admin --}}
                @if (Auth::user()->rol_id == 1)
                <th scope="col">Sucursal</th>
                @endif
                <th scope="col">Subtotal</th>
                <th scope="col">Descuento</th>
                <th scope="col">Total</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="mydata">
            @foreach ($sales as $item)
            @if($item->user_id == Auth::user()->id || Auth::user()->rol_id == "1")

            <tr>
                <th scope="row">{{$item->id}}</th>
                <th>{{$item->folio_branch_office}}</th>
                <td>{{$item->user->name}}</td>
                @if (Auth::user()->rol_id == 1)
                <td>{{$item->branchOffice->name}}</td>
                @endif
                <td>{{$item->cart_subtotal}}</td>
                <td>{{$item->amount_discount}}</td>
                <td>{{$item->cart_total}}</td>
                <td>
                    <div class="row">
                        @if (Auth::user()->rol_id == 1)
                        <form  action="/reprint" method="POST" target="_blank">
                            <input type="hidden" name="sale_id" value="{{$item->id}}">
                            <button type="submit" class="btn btn-outline-secondary btn-sm mx-2" data-type="edit">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                </svg>
                                <small>REIMPRIMIR TICKET</small>
                            </button>
                        </form>

                        <form onsubmit="return confirm('Cancelar esta venta?')" action="/sale/{{$item->id}}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger btn-sm  mx-2" data-type="delete">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z" />
                                </svg>
                                <small>CANCELAR</small>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
                <td>
                    <a href="{{asset('sale-detail/'.$item->id.'')}}" class="btn btn-primary btn-sm mx-2"><small>DETALLES</small></a>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @else
    <table class="display table table-striped table-bordered" id="example" style="width:100%">
        <thead class="black white-text">
            <tr>
                <th scope="col">Folio general</th>
                <th scope="col">Folio sucursal</th>
                <th scope="col">Empleado</th>
                {{-- solo si es admin --}}
                @if (Auth::user()->rol_id == 1)
                <th scope="col">Sucursal</th>
                @endif
                <th scope="col">Subtotal</th>
                <th scope="col">Descuento</th>
                <th scope="col">Total</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="mydata">
            @foreach ($sales as $item)
            <tr>
                <th scope="row">{{$item->id}}</th>
                <th>{{$item->folio_branch_office}}</th>
                <td>{{$item->user->name}}</td>
                @if (Auth::user()->rol_id == 1)
                <td>{{$item->branchOffice->name}}</td>
                @endif
                <td>{{$item->cart_subtotal}}</td>
                <td>{{$item->amount_discount}}</td>
                <td>{{$item->cart_total}}</td>
                <td>
                    <div class="row">
                        <form  action="/reprint" method="POST" target="_blank">
                            <input type="hidden" name="sale_id" value="{{$item->id}}">
                            <button type="submit" class="btn btn-outline-secondary btn-sm mx-2" data-type="edit">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                </svg>
                                <small>REIMPRIMIR TICKET</small>
                            </button>
                        </form>
                        @if (Auth::user()->rol_id == 1)
                        <form onsubmit="return confirm('Cancelar esta venta?')" action="/sale/{{$item->id}}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger btn-sm  mx-2" data-type="delete">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z" />
                                </svg>
                                <small>CANCELAR</small>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
                <td>
                    <a href="{{asset('sale-detail/'.$item->id.'')}}" class="btn btn-primary btn-sm mx-2"><small>DETALLES</small></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
@push('scripts')
<script>
    function closeModal() {
        $('#openCashBoxModal').modal('hide');
        $('#closeBoxButton').prop('hidden', true);
    }

    function encode_utf8(s) {
        return unescape(encodeURIComponent(s));
    }

    function pay(item, rol) {

        console.log(encode_utf8(item.products_in_sale[0].product.name))
        if (confirm('Reimprimir?')) {

            // if(item.payment_type == 1){
            //     item.payment_type = 0
            // }
            // else{
            //     item.payment_type = 1
            // }                

            if (rol != 1) {
                item.products_in_sale.forEach(element => {
                    element.product.category.name = "N/S"
                });
            }
            item.products_in_sale.forEach(element => {
                element.product.name = encode_utf8(element.product.name)
            });

            console.log(item)
            let request = {
                data: item
            };

            $.ajax({
                url: "http://localhost/reprint",
                type: 'POST',
                contentType: "application/json; charset=iso-8859-1",
                data: JSON.stringify(request),
                dataType: 'html',
                success: function(data) {
                    console.log(data)
                    if (JSON.parse(data).success) {
                        alert('Impreso correctamente')
                        //location.reload();
                    } else {
                        console.log(JSON.parse(data));
                    }
                },
                error: function(e) {
                    console.log("ERROR", e);
                    alert('Error al imprimir')
                },

            });
        }
        console.log('fin')
    }
</script>
@endpush