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
    <div class="modal fade" id="deleteModalExpense" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalExpenseLabel">Eliminar</h5>
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
   
    <!-- Edit Modal -->
    <div class="modal fade" id="productModalEdit" tabindex="-1" role="dialog" aria-labelledby="productModalEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalEditLabel">Editar gasto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myFormEdit" action="/expense" method="post" onsubmit="upperCreate()">
                        @csrf
                        @method('PUT')                        
                        <div class="form-group my-3 mx-3">
                            <label for="user">Usuario</label>
                            <input readonly="readonly" class="form-control" type="text" name="name_edit" id="name_edit" placeholder="Usuario autorizado" required>
                        </div>  
                        <div class="form-group  my-3 mx-3">
                            <label for="name">Descripción</label>
                            <input  style="text-transform: uppercase" class="form-control" type="text" name="description" id="description_edit" placeholder="Descripcion" required>
                        </div>
                        <div class="form-group  my-3 mx-3">
                            <label for="name">Cantidad</label>
                            <input type="number" class="form-control" name="quantity" id="quantity_edit" placeholder="Descripcion" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="stock">Precio</label>
                            <input class="form-control" type="number" name="price" id="price_edit" placeholder="Precio por pieza" required>
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
  
    <form action="/expenses" method="get">
        @csrf
        <div style="text-align:right">
        <button type="submit"  class="btn  btn-outline-primary my-2" ><small>CREAR</small></button>
        </div>
    </form>
    <input class="form-control" type="text" name="buscador" id="buscador" placeholder="Buscar...">
    <table class="table table-striped">
        <thead class="black white-text">
            <tr>
                <th scope="col">Usuario</th>
                <th scope="col">Descripción</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Precio/Pieza</th>
                <th scope="col">Total</th>                
                <th scope="col">Numero de caja</th>
                @if (Auth::user()->rol_id == 1)
                <th scope="col">Sucursal</th>
                @endif

                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="mydata">
            @foreach ($expenses as $item)
            <tr>
                <th scope="row">{{$item->user->name}}</th>
                <td>{{$item->description}}</td>
                <td>{{$item->quantity}}</td>
                <td>{{$item->price}}</td>
                <td>${{$item->price*$item->quantity}}</td>
                <td>{{$item->cash_closing->id}}</td>                
                @if (Auth::user()->rol_id == 1)
                <td>{{$item->branch_office->name}}</td>
                @endif
                <td>
                    <button onclick="llenar({{$item}})" type="button" class="btn btn-outline-secondary btn-sm " data-toggle="modal" data-target="#productModalEdit">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                        </svg>  
                            <small>EDITAR</small>
                        </button>
                        <button  data-type="delete" data-toggle="modal" data-target="#deleteModalExpense" data-id="{{$item->id}}" class="btn btn-outline-danger btn-sm">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                        </svg>
                        <small>ELIMINAR</small>
                        </button>
                </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>    
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $("#buscador").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#mydata tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $('button').click(function() {
            if($(this).data('type')=='delete'){
                $('#deleteForm').attr('action','expense/'+$(this).data('id'));
                $('#deleteModal').modal();
            }});
    });

    function limpiar(){
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

    function llenar(item){        
        document.getElementById("myFormEdit").action = "/expense/"+item.id;        
        document.getElementById('name_edit').value = item.user.name
        document.getElementById('description_edit').value = item.description
        document.getElementById('quantity_edit').value = item.quantity
        document.getElementById('price_edit').value = item.price       
    }

    function upperCreate(){
        document.getElementById('description_edit').value = document.getElementById('description_edit').value.toUpperCase()

        return true;
    }
</script>
@endpush