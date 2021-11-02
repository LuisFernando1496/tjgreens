@extends('layouts.app')

@section('content')
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
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="updateForm" method="POST">
        @method('PUT')
        @csrf
        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="categoryNameToUpdate">Nombre de la categoría</label>
            <input type="text" class="form-control" name="name" id="categoryNameToUpdate" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">CANCELAR</button>
          <button type="submit" class="btn btn-primary">GUARDAR</button>
        </div>
      </form>
    </div>
  </div>
</div>
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

  <div class="card p-4">
    <div class="mb-2">
      <h5>Agregar categoría</h5>
    </div>
    <form action="categorias" method="POST">
      @csrf
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="name" placeholder="Nombre de la categoría" required>
        <div class="input-group-append">
          <button class="btn btn-outline-primary" type="submit" id="button-addon2">AGREGAR</button>
        </div>
      </div>
    </form>
    <div class="mb-2 mt-3">
      <h5>Lista de categorías</h5>
    </div>
    <table class="display table table-striped table-bordered" id="example" style="font-size:0.85em; width:100%">
      <thead class="black white-text">
        <tr>
          <th scope="col">Nombre</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($categories as $category)
        <tr>
          <th scope="row">{{$category->name}}</th>
          <td>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-id="{{$category->id}}" data-name="{{$category->name}}" data-type="edit" data-toggle="modal" data-target="#updateModal">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
              </svg>
              EDITAR
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm" data-id="{{$category->id}}" data-type="delete" data-type="edit" data-toggle="modal" data-target="#deleteModal">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z" />
              </svg>
              ELIMINAR
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@push('scripts')
<script type="application/javascript">
  $(document).ready(function() {
    $('button').click(function() {
      if ($(this).data('type') == 'delete') {
        $('#deleteForm').attr('action', 'categorias/' + $(this).data('id'));
        // $('#deleteModal').modal();
      }
      if ($(this).data('type') == 'edit') {
        $('#categoryNameToUpdate').val($(this).data('name'));
        $('#updateForm').attr('action', 'categorias/' + $(this).data('id'));
        // $('#updateModal').modal();
      }
    });
  })
</script>
@endpush
@endsection