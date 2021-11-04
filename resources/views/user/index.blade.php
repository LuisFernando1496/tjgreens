@extends('layouts.app')

@section('content')
<div class="container">
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
                    <form id="myForm" action="/registro" method="post" onsubmit="upperCreate()">
                        @csrf
                        <div class="form-group my-3 mx-3">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" name="name" id="name" value="" placeholder="Nombre(s)" required />
                        </div>
                        <div class="form-group  my-3 mx-3">
                            <label for="last_name">Apellido</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" value="" placeholder="Apellido(s)" required />
                        </div>
                       {{--  <div class="form-group my-3 mx-3">
                            <label for="rfc">RFC</label>
                            <input type="text" class="form-control" name="rfc" id="rfc" placeholder="RFC" value="" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="curp">CURP</label>
                            <input type="text" class="form-control" name="curp" id="curp" placeholder="CURP" value="" required />
                        </div>--}}
                        <div class="form-group my-3 mx-3">
                            <label for="phone">Teléfono</label>
                            <input type="number" class="form-control" name="phone" id="phone" placeholder="Tel cel." value="" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="email">Correo electrónico</label>
                            <input type="email" class="form-control" name="email" id="email" value="" placeholder="example@gmail.com" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" name="password" value="" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="password-confirm">Confirmar contraseña</label>
                            <input type="password" class="form-control" name="password-confirm" value="" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="street">Calle / avenida</label>
                            <input type="text" class="form-control" name="street" id="street" value="" placeholder="Calle" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="int_number">Número interior</label>
                            <input type="text" class="form-control" name="int_number" id="int_number" placeholder="123" value="" />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="ext_number">Número exterior</label>
                            <input type="text" class="form-control" name="ext_number" id="ext_number" placeholder="234" value="" />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="suburb">Colonia / barrio</label>
                            <input type="text" class="form-control" name="suburb" id="suburb" value="" placeholder="Colonia" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="postal_code">Código postal</label>
                            <input type="number" class="form-control" name="postal_code" id="postal_code" value="" placeholder="29000" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="city">Ciudad</label>
                            <input type="text" class="form-control" name="city" id="city" value="" placeholder="Ciudad" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="state">Estado</label>
                            <select class="form-control" type="text" name="state" id="state" value="" required>
                                <option value="Ciudad de México">Ciudad de México</option>
                                <option value="Aguascalientes">Aguascalientes</option>
                                <option value="Baja California">Baja California</option>
                                <option value="Baja California sur">Baja California Sur</option>
                                <option value="Campeche">Campeche</option>
                                <option value="Chiapas">Chiapas</option>
                                <option value="Chihuahua">Chihuahua</option>
                                <option value="Coahuila">Coahuila</option>
                                <option value="Colima">Colima</option>
                                <option value="Durango">Durango</option>
                                <option value="Guanajuato">Guanajuato</option>
                                <option value="Guerrero">Guerrero</option>
                                <option value="Hidalgo">Hidalgo</option>
                                <option value="Jalisco">Jalisco</option>
                                <option value="Cd. México">Cd. México</option>
                                <option value="Michoacán">Michoacán</option>
                                <option value="Morelos">Morelos</option>
                                <option value="Nayarit">Nayarit</option>
                                <option value="Nuevo León">Nuevo León</option>
                                <option value="Oaxaca">Oaxaca</option>
                                <option value="Puebla">Puebla</option>
                                <option value="Querétaro">Querétaro</option>
                                <option value="Quintana Roo">Quintana Roo</option>
                                <option value="San Luis Potosí">San Luis Potosí</option>
                                <option value="Sinaloa">Sinaloa</option>
                                <option value="Sonora">Sonora</option>
                                <option value="Tabasco">Tabasco</option>
                                <option value="Tamaulipas">Tamaulipas</option>
                                <option value="Tlaxcala">Tlaxcala</option>
                                <option value="Veracruz">Veracruz</option>
                                <option value="Yucatán">Yucatán</option>
                                <option value="Zacatecas">Zacatecas</option>
                            </select>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="country">País</label>
                            <input type="text" class="form-control" name="country" id="country" value="" placeholder="País" required />
                        </div>

                        {{-- solo si es admin --}}
                        <div class="form-group my-3-mx-3">
                            <select class="browser-default custom-select" name="rol_id" id="rol_id" required>
                                <option value="0" disabled selected>Rol:</option>
                                @foreach($rols as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group my-3-mx-3">
                            <select class="browser-default custom-select" name="branch_office_id" id="branch_office_id" required>
                                <option value="0" disabled selected>Sucursal:</option>
                                @foreach($offices as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach

                            </select>
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
                       {{--  <div class="form-group my-3 mx-3">
                            <label for="rfc">RFC</label>
                            <input type="text" class="form-control" name="rfc" id="rfc_edit" placeholder="RFC" value="" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="curp">CURP</label>
                            <input type="text" class="form-control" name="curp" id="curp_edit" placeholder="CURP" value="" required />
                        </div>--}}
                        <div class="form-group my-3 mx-3">
                            <label for="phone">Teléfono</label>
                            <input type="number" class="form-control" name="phone" id="phone_edit" placeholder="Tel cel." value="" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="email">Correo electrónico</label>
                            <input type="email" class="form-control" name="email" id="email_edit" value="" placeholder="example@gmail.com" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="street">Calle / avenida</label>
                            <input type="text" class="form-control" name="street" id="street_edit" value="" placeholder="Calle" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="int_number">Número interior</label>
                            <input type="text" class="form-control" name="int_number" id="int_number_edit" placeholder="123" value="" />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="ext_number">Número exterior</label>
                            <input type="text" class="form-control" name="ext_number" id="ext_number_edit" placeholder="234" value="" />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="suburb">Colonia / barrio</label>
                            <input type="text" class="form-control" name="suburb" id="suburb_edit" value="" placeholder="Colonia" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="postal_code">Código postal</label>
                            <input type="number" class="form-control" name="postal_code" id="postal_code_edit" value="" placeholder="29000" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="city">Ciudad</label>
                            <input type="text" class="form-control" name="city" id="city_edit" value="" placeholder="Ciudad" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="state">Estado</label>
                            <input type="text" class="form-control" name="state" id="state_edit" value="" placeholder="Estado" required />
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="country">País</label>
                            <input type="text" class="form-control" name="country" id="country_edit" value="" placeholder="País" required />
                        </div>

                        {{-- solo si es admin --}}
                        <div class="form-group my-3-mx-3">
                            <select class="browser-default custom-select" name="rol_id" id="rol_id_edit" required>
                                <option value="0" disabled selected>Rol:</option>
                                @foreach($rols as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group my-3-mx-3">
                            <select class="browser-default custom-select" name="branch_office_id" id="branch_office_id_edit" required>
                                <option value="0" disabled selected>Sucursal:</option>
                                @foreach($offices as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach

                            </select>
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

    {{-- Cambio contraseña --}}
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Cambiar contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="passwordForm" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="newPass">Nueva contraseña</label>
                            <input type="password" class="form-control" name="newPass" id="newPass">
                        </div>
                        <div class="form-group">
                            <label for="newPass2">Confirmar contraseña</label>
                            <input type="password" class="form-control" name="newPass2" id="newPass2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">CANCELAR</button>
                        @csrf
                        <button type="submit" class="btn btn-warning">CAMBIAR</button>
                    </div>
                </form>
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
                <th scope="col">RFC</th>
                <th scope="col">CURP</th>
                <th scope="col">Teléfono</th>
                @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                <th scope="col">Sucursal</th>
                @endif
                <th scope="col">Rol</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="mydata">
            @foreach ($users as $item)
            <tr>
                <th scope="row">{{$item->email}}</th>
                <td>{{$item->name}}</td>
                <td>{{$item->last_name}}</td>
                <td>{{$item->rfc}}</td>
                <td>{{$item->curp}}</td>
                <td>{{$item->phone}}</td>
                @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                <td>{{$item->branchOffice->name}}</td>
                @endif
                <td>{{$item->rol->name}}</td>
                <td>
                    @if ($item->rol_id != 1)
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
                    @endif
                    @if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                        @if ($item->rol_id != 1)
                        <button type="button" class="btn btn-sm btn-outline-warning" data-type="update" data-toggle="modal" data-target="#passwordModal" data-id="{{$item->id}}">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-asterisk" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z" />
                            </svg>
                            <small>PASSWORD</small>
                        </button>
                        @else
                        @endif
                    @endif
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
        document.getElementById('rfc_edit').value = item.rfc
        document.getElementById('curp_edit').value = item.curp
        document.getElementById('phone_edit').value = item.phone
        document.getElementById('email_edit').value = item.email
        document.getElementById('street_edit').value = item.address.street
        document.getElementById('int_number_edit').value = item.address.int_number
        document.getElementById('ext_number_edit').value = item.address.ext_number
        document.getElementById('suburb_edit').value = item.address.suburb
        document.getElementById('postal_code_edit').value = item.address.postal_code
        document.getElementById('city_edit').value = item.address.city
        document.getElementById('state_edit').value = item.address.state
        document.getElementById('country_edit').value = item.address.country
        document.getElementById('rol_id_edit').value = item.rol.id
        document.getElementById('branch_office_id_edit').value = item.branch_office.id

    }

    function upperCreate() {
        document.getElementById('name').value = document.getElementById('name').value.toUpperCase()
        document.getElementById('name_edit').value = document.getElementById('name_edit').value.toUpperCase()
        document.getElementById('last_name').value = document.getElementById('last_name').value.toUpperCase()
        document.getElementById('last_name_edit').value = document.getElementById('last_name_edit').value.toUpperCase()
        document.getElementById('rfc').value = document.getElementById('rfc').value.toUpperCase()
        document.getElementById('rfc_edit').value = document.getElementById('rfc_edit').value.toUpperCase()
        document.getElementById('crup').value = document.getElementById('crup').value.toUpperCase()
        document.getElementById('curp_edit').value = document.getElementById('curp_edit').value.toUpperCase()

        return true;
    }
</script>
@endpush