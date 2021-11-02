@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
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
    <!-- Create Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Nuevo usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myForm" action="/users" method="post" onsubmit="upperCreate()">
                        @csrf
                        <div class="form-group my-3 mx-3">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" name="name" id="name" value="" placeholder="Nombre(s)" required />
                        </div>
                        <div class="form-group  my-3 mx-3">
                            <label for="last_name">Apellido</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" value="" placeholder="Apellido(s)" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="phone">Teléfono</label>
                            <input type="number" class="form-control" name="phonenumber" id="phone" placeholder="Tel cel." value="" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="email">Correo electrónico</label>
                            <input type="email" class="form-control" name="email" id="email" value="" placeholder="example@gmail.com" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="address">Dirección</label>
                            <textarea class="form-control" type="text" name="address" id="address" value="" placeholder="9na Sur" required></textarea>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="authorized_credit">Crédito autorizado</label>
                            <input type="number" class="form-control" name="authorized_credit" id="authorized_credit" value="" required />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="userModalEdit" tabindex="-1" role="dialog" aria-labelledby="userModalEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalEditLabel">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myFormEdit" action="/users" method="post" onsubmit="upperCreate()">
                        @csrf
                        @method('PUT')
                        <div class="form-group my-3 mx-3">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" name="name" id="name_edit" value="" placeholder="Nombre(s)" required />
                        </div>
                        <div class="form-group  my-3 mx-3">
                            <label for="last_name">Apellido</label>
                            <input type="text" class="form-control" name="last_name" id="last_name_edit" value="" placeholder="Apellido(s)" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="phone">Teléfono</label>
                            <input type="number" class="form-control" name="phonenumber" id="phone_edit" placeholder="Tel cel." value="" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="email">Correo electrónico</label>
                            <input type="email" class="form-control" name="email" id="email_edit" value="" placeholder="example@gmail.com" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="address">Dirección</label>
                            <textarea class="form-control" type="text" name="address" id="address_edit" value="" placeholder="9na Sur" required></textarea>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="authorized_credit">Crédito autorizado</label>
                            <input type="number" class="form-control" name="authorized_credit" id="authorized_credit_edit" value="" required />
                        </div>
                        <input type="hidden" name="client_id" id="client_id">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Eliminar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de eliminar este elemento?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">CANCELAR</button>
                    <form id="deleteForm" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="eliminate_client" id="eliminate_client">
                        <button type="submit" class="btn btn-danger">ELIMINAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align:right">
        <button onclick="limpiar()" type="button" class="btn btn-outline-primary my-2" data-toggle="modal" data-target="#userModal"><small>CREAR</small></button>
    </div>
    <table class="display table table-striped table-bordered" id="example" tyle="font-size:0.85em; width:100%">
        <thead class="black white-text">
            <tr>
                <th scope="col">Correo electrónico</th>
                <th scope="col">Nombre(s)</th>
                <th scope="col">Apellido(s)</th>
                <th scope="col">Teléfono</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="mydata">
            @foreach ($users as $item)
            @if ($last === $item->id)
                <tr class="table-success">
            @else
                <tr>
            @endif
                <th scope="row">{{$item->email}}</th>
                <td>{{$item->name}}</td>
                <td>{{$item->last_name}}</td>
                <td>{{$item->phonenumber}}</td>
                <td>
                    <button onclick="llenar({{$item}})" type="button" class="btn btn-outline-secondary btn-sm my-2" data-toggle="modal" data-target="#userModalEdit">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                        </svg>
                        <small>EDITAR</small>
                    </button>
                    <button data-type="delete" data-target="#deleteModal" data-id="{{$item->id}}" class="btn btn-outline-danger btn-sm my-2" data-toggle="modal" data-target="#deleteModal">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z" />
                        </svg>
                        <small>ELIMINAR</small>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('button').click(function() {
            if ($(this).data('type') == 'delete') {
                $('#deleteForm').attr('action', 'users/' + $(this).data('id'));
                document.getElementById('eliminate_client').value = $(this).data('id');
                // $('#deleteModal').modal();
            } else if ($(this).data('type') == 'update') {
                $('#passwordForm').attr('action', 'changePassword/' + $(this).data('id'));
                $('#passwordModal').modal();
            }
        });
    });

    function limpiar() {
        let fields = document.getElementsByClassName('form-control')

        let selects = document.getElementsByClassName('custom-select')

        for (let i = 0; i < selects.length; i++) {
            var element = selects[i];
            element.value = '0'
        }

        for (let i = 0; i < fields.length; i++) {
            var element = fields[i];
            element.value = ''
        }
    }

    function llenar(item) {
        document.getElementById("myFormEdit").action = "/users/" + item.id;

        document.getElementById('name_edit').value = item.name
        document.getElementById('last_name_edit').value = item.last_name
        document.getElementById('phone_edit').value = item.phonenumber
        document.getElementById('email_edit').value = item.email
        document.getElementById('client_id').value = item.id
        document.getElementById('address_edit').value = item.address
        document.getElementById('authorized_credit_edit').value = item.authorized_credit
    }

    function upperCreate() {
        document.getElementById('name').value = document.getElementById('name').value.toUpperCase()
        document.getElementById('name_edit').value = document.getElementById('name_edit').value.toUpperCase()
        document.getElementById('last_name').value = document.getElementById('last_name').value.toUpperCase()
        document.getElementById('last_name_edit').value = document.getElementById('last_name_edit').value.toUpperCase()
        return true;
    }
</script>
@endpush
