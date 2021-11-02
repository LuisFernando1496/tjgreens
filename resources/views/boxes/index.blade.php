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
          <div class="form-group">
            <label for="boxNumberToUpdate">Número de la caja</label>
            <input type="number" style="text-transform: uppercase" class="form-control" name="number" id="boxNumberToUpdate" min="1" required>
          </div>
          <div class="form-group mb-3">
            <label for="branchOfficeToUpdate">Sucursal</label>
            <select id="branchOfficeToUpdate" class="custom-select" name="branch_office_id" placeholder="Sucursal" required>
              @foreach ($branch_office as $branch)
              <option value="{{$branch->id}}">{{$branch->name}}</option>
              @endforeach
            </select>
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
      <h5>Agregar caja</h5>
    </div>
    <form action="box" method="POST">
      @csrf
      <div class="input-group my-2">
        <div class="input-group-prepend">
          <span class="input-group-text" id="branch_office_id">Sucursal</span>
        </div>
        <select id="branch_office_id" class="custom-select" name="branch_office_id" placeholder="Sucursal" required>
          @foreach ($branch_office as $branch)
          <option value="{{$branch->id}}">{{$branch->name}}</option>
          @endforeach
        </select>
      </div>
      <div class="input-group my-3">
        <div class="input-group-prepend">
          <span class="input-group-text">Número de la caja</span>
        </div>
        <input type="text" class="form-control" name="number" required>
        <div class="input-group-append">
          <button class="btn btn-outline-primary" type="submit" id="button-addon">AGREGAR</button>
        </div>
      </div>
    </form>
    <div class="mb-2 mt-3">
      <h5>Lista de cajas</h5>
    </div>
    <table class="display table table-striped table-bordered" id="example" style="font-size:0.85em; width:100%">
      <thead class="black white-text">
        <tr>
          <th scope="col">Número de caja</th>
          <th scope="col">Sucursal de la caja</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($boxes as $box)
        <tr>
          <th scope="row">{{$box->number}}</th>
          <td>{{$box->branchOffice->name}}</td>
          <td><button type="button" class="btn btn-outline-secondary btn-sm" data-id="{{$box->id}}" data-branch="{{$box->branchOffice->id}}" data-number="{{$box->number}}" data-type="edit" data-toggle="modal" data-target="#updateModal">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
              </svg>
              EDITAR
            </button></td>
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
        $('#deleteForm').attr('action', 'box/' + $(this).data('id'));
        $('#deleteModal').modal();
      }
      if ($(this).data('type') == 'edit') {
        $('#boxNumberToUpdate').val($(this).data('number'));
        $('#branchOfficeToUpdate').val($(this).data('branch'));
        $('#updateForm').attr('action', 'box/' + $(this).data('id'));
        // $('#updateModal').modal();
      }
    });
  })
</script>
@endpush
@endsection