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
        <div class="card p-4 mt-4">
            <div class="row my-4">
                <div class="col-md-12">
                    <h4 class="mx-4">
                        Información personal 
                        <button type="button" id="editPersonalInformationButton" class="btn btn-light btn-sm mx-2">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                            </svg>
                            <small>EDITAR</small>
                        </button>
                    </h4>
                </div>
                <form action="users/{{$user->id}}" id="personalInformationForm" class="row mx-4">
                    @method('PUT')
                    @csrf   
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}" required readonly/>
                        </div>
                    </div>  
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_name">Apellido</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" value="{{$user->last_name}}" required readonly/>
                        </div>
                    </div>  
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc">RFC</label>
                            <input type="text" class="form-control" name="rfc" id="rfc" value="{{$user->rfc}}" required readonly/>
                        </div>
                    </div>  
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="curp">CURP</label>
                            <input type="text" class="form-control" name="curp" id="curp" value="{{$user->curp}}" required readonly/>
                        </div>
                    </div>   
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{$user->email}}" required readonly/>
                        </div>
                    </div>  
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Teléfono</label>
                            <input type="number" class="form-control" name="phone" id="phone" value="{{$user->phone}}" required readonly/>
                        </div>
                    </div>  
                    <div class="col mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="street">Calle / avenida</label>
                                    <input type="text" class="form-control" name="street" id="street" value="{{$user->address->street}}" required readonly/>
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="int_number">Número interior</label>
                                    <input type="text" class="form-control" name="int_number" id="int_number" value="{{$user->address->int_number}}" readonly/>
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ext_number">Número exterior</label>
                                    <input type="text" class="form-control" name="ext_number" id="ext_number" value="{{$user->address->ext_number}}" readonly/>
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="suburb">Colonia / barrio</label>
                                    <input type="text" class="form-control" name="suburb" id="suburb" value="{{$user->address->suburb}}" required readonly/>
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="postal_code">Código postal</label>
                                    <input type="number" class="form-control" name="postal_code" id="postal_code" value="{{$user->address->postal_code}}" required readonly/>
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">Ciudad</label>
                                    <input type="text" class="form-control" name="city" id="city" value="{{$user->address->city}}" required readonly/>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state">Estado</label>
                                    <input type="text" class="form-control" name="state" id="state" value="{{$user->address->state}}" required readonly/>
                                </div>
                            </div>   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">País</label>
                                    <input type="text" class="form-control" name="country" id="country" value="{{$user->address->country}}" required readonly/>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="button" id="cancelEditAddressButton" class="btn btn-outline-danger mx-2" hidden>CANCELAR</button>
                        <button type="submit" id="submitEditAddressButton" class="btn btn-primary mx-2" hidden>GUARDAR</button>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="button" id="cancelEditPersonalInformationButton" class="btn btn-outline-danger mx-2" hidden>CANCELAR</button>
                        <button type="submit" id="submitEditPersonalInformationButton" class="btn btn-primary mx-2" hidden>GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="application/javascript">
            $(document).ready(function() {
                $('#editPersonalInformationButton').click(function() {
                    $(this).prop('hidden',true);
                    $('#cancelEditPersonalInformationButton').prop('hidden',false);
                    $('#submitEditPersonalInformationButton').prop('hidden',false);
                    $("#personalInformationForm").each(function(){
                        $(this).find(':input').each(function() {
                            $( this ).prop( "readonly", false );
                        });
                    });
                });
                $('#cancelEditPersonalInformationButton').click(function() {
                    $('#editPersonalInformationButton').prop('hidden',false);
                    $('#cancelEditPersonalInformationButton').prop('hidden',true);
                    $('#submitEditPersonalInformationButton').prop('hidden',true);
                    $("#personalInformationForm").each(function(){
                        $(this).find(':input').each(function() {
                            $( this ).prop( "readonly", true );
                        });
                    });
                });
            }) 
        </script>
    @endpush
@endsection