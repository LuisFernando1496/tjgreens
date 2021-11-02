@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Create Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Nueva sucursal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myForm" action="/branchOffice" method="post">
                        @csrf
                        <div class="form-group  my-3 mx-3">
                            <label for="name">Nombre</label>
                            <input class="form-control" type="text" name="name" id="name" placeholder="Nombre" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="street">Calle</label>
                            <input class="form-control" type="text" name="street" id="street" placeholder="Calle" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="ext_number">No. Exterior</label>
                            <input class="form-control" type="text" name="ext_number" id="ext_number" placeholder="No. Exterior" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="int_number">No. Interior</label>
                            <input class="form-control" type="text" name="int_number" id="int_number" placeholder="No. Interior" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="suburb">Colonia</label>
                            <input class="form-control" type="text" name="suburb" id="suburb" placeholder="Colonia" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="postal_code">Código postal</label>
                            <input class="form-control" type="number" name="postal_code" id="postal_code" placeholder="Descuento" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="city">Ciudad</label>
                            <input class="form-control" type="text" name="city" id="city" placeholder="Ciudad" required>
                        </div>   
                        <div class="form-group my-3 mx-3">
                            <label for="state">Estado</label>
                            <input class="form-control" type="text" name="state" id="state" placeholder="Estado" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="country">País</label>
                            <input class="form-control" type="text" name="country" id="country" placeholder="País" required>
                        </div>               
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-outline-primary">Guardar</button>
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
                    <h5 class="modal-title" id="productModalEditLabel">Editar sucursal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myFormEdit" action="/BranchOffice" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group  my-3 mx-3">
                            <label for="name">Nombre</label>
                            <input class="form-control" type="text" name="name" id="name_edit" placeholder="Nombre" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="street">Calle</label>
                            <input class="form-control" type="text" name="street" id="street_edit" placeholder="Calle" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="ext_number">No. Exterior</label>
                            <input class="form-control" type="text" name="ext_number" id="ext_number_edit" placeholder="No. Exterior" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="int_number">No. Interior</label>
                            <input class="form-control" type="text" name="int_number" id="int_number_edit" placeholder="No. Interior" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="suburb">Colonia</label>
                            <input class="form-control" type="text" name="suburb" id="suburb_edit" placeholder="Colonia" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="postal_code">Código postal</label>
                            <input class="form-control" type="number" name="postal_code" id="postal_code_edit" placeholder="Descuento" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="city">Ciudad</label>
                            <input class="form-control" type="text" name="city" id="city_edit" placeholder="Ciudad" required>
                        </div>   
                        <div class="form-group my-3 mx-3">
                            <label for="state">Estado</label>
                            <input class="form-control" type="text" name="state" id="state_edit" placeholder="Estado" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="country">País</label>
                            <input class="form-control" type="text" name="country" id="country_edit" placeholder="País" required>
                        </div>               
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-outline-primary">Guardar</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>

    <button onclick="limpiar()" type="button" class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#productModal">Crear</button>
    
    <table class="display table table-striped table-bordered" id="example" style="font-size:0.85em; width:100%">
        <thead class="black white-text">
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Calle</th>
                <th scope="col">Colonia</th>
                <th scope="col">No. Exterior</th>
                <th scope="col">No. Interior</th>
                <th scope="col">Código Postal</th>
                <th scope="col">Ciudad</th>                
                <th scope="col">Estado</th>
                <th scope="col">País</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="mydata">
            @foreach ($office as $item)
            <tr>
                <th scope="row">{{$item->name}}</th>
                <td>{{$item->address->street}}</td>
                <td>{{$item->address->suburb}}</td>
                <td>{{$item->address->ext_number}}</td>
                <td>{{$item->address->int_number}}</td>
                <td>{{$item->address->postal_code}}</td>
                <td>{{$item->address->city}}</td>
                <td>{{$item->address->state}}</td>
                <td>{{$item->address->country}}</td>
                <td>                    
                    <button onclick="llenar({{$item}})" type="button" class="btn btn-outline-secondary btn-sm"Sdata-type="edit" data-toggle="modal" data-target="#productModalEdit">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                        </svg>  
                            Editar
                        </button> 
                    @if (Auth::user()->rol_id == 1)
                    <form onsubmit="return confirm('Eliminar sucursal?')" action="/BranchOffice/{{$item->id}}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-outline-danger btn-sm" data-type="delete">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                            </svg>   
                            Eliminar
                        </button> 
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>    
</div>
<script>

    function limpiar(){
        let fields = document.getElementsByClassName('form-control')        

        for (let i = 0; i < fields.length; i++) {            
            var element = fields[i];
            element.value = ''
        }
    }

    function llenar(item){
        document.getElementById("myFormEdit").action = "/branchOffice/"+item.id;     

        document.getElementById('name_edit').value = item.name
        document.getElementById('street_edit').value = item.address.street
        document.getElementById('ext_number_edit').value = item.address.ext_number
        document.getElementById('int_number_edit').value = item.address.int_number
        document.getElementById('suburb_edit').value = item.address.suburb
        document.getElementById('postal_code_edit').value = item.address.postal_code
        document.getElementById('city_edit').value = item.address.city
        document.getElementById('state_edit').value = item.address.state
        document.getElementById('country_edit').value = item.address.country
        
    }
</script>
@endsection
