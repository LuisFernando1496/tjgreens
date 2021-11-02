@extends('layouts.app')
@section('content')
<div class="container">
    <div class="modal fade" id="userModalEdit" tabindex="-1" role="dialog" aria-labelledby="userModalEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalEditLabel">Editar monto inicial</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myFormEdit" action="/initialCash" method="post">
                        @csrf
                        @method('PUT')                        
                        <div class="form-group my-3 mx-3">
                            <label for="name">Monto</label>
                            <input type="number" min=0 class="form-control" name="amount" id="amount" value=""placeholder="Monto inicial" required />
                        </div>                                   
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">CANCELAR</button>
                            <button type="submit" class="btn btn-primary">GUARDAR</button>
                        </div>
                    </form>
                </div>           
            </div>
        </div>
    </div>
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
        @if(isset($flag))
            <div class="mb-2">
                <h5>Monto inicial</h5>
                <h6 style="color: red;">*Este valor será el dinero inicial al abrir todas las cajas*</h6>
            </div>
            <ul class="list-group">
            @if(count($amount)==0)
                <li class="list-group-item text-center">
                    Aún no ha definido un monto inicial
                </li>
            @endif
            @foreach($amount as $item)
                <li class="list-group-item">
                <div class="row">
                    <div class="col-md-10">
                    {{$item->amount}}
                    </div>
                    <div class="col-md-2 d-flex justify-content-center">
                        <button onclick="llenar({{$item}})" type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal"  data-target="#userModalEdit">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                        </svg>  
                            <small>EDITAR</small>
                        </button>
                    </div>
                </div>
                </li>
            @endforeach
            </ul>
        @else
            <div class="mb-2">
                <h5>Seleccionar monto inicial</h5>
                <h6 style="color: red;">*Este valor será el dinero inicial al abrir todas las cajas*</h6>
            </div>
            <form action="initialCash" method="POST">
                @csrf
                <div class="input-group my-3">
                    <div class="input-group-prepend">
                    <span class="input-group-text">Monto inicial</span>
                    </div>
                    <input type="number" class="form-control" name="amount" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="button-addon">AGREGAR</button>
                    </div>
                </div>  
            </form>
        @endif   
    </div>
</div>
@endsection
@push('scripts')
<script>
    function llenar(item){        
        document.getElementById("myFormEdit").action = "/initialCash/"+item.id;        
        
        document.getElementById('amount').value = item.amount
    }
</script>
@endpush