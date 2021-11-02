@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
    <div class="card p-4">
        <div class="mb-2">
          <h5>Generar reporte</h5>
        </div>
        <form id="request" onsubmit="addRequestParams()">
            @csrf  
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="type">Tipo de reporte</label>
                        <select class="custom-select" id="type" required>
                            <option value="1">General</option>
                            <option value="2">Por sucursal</option>
                            <option value="3">Por sucursal y empleado</option>
                            <option value="4">Inventario</option>
                            <option value="5">Inventario por sucursal</option>
                        </select>   
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" id="view_branch" hidden>
                    <div class="form-group">
                        <label for="branch">Sucursal</label>
                        <select class="custom-select" name="branchOfficeId" id="branch">
                            @foreach($offices as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>   
                    </div>
                </div>
                <div class="col-md-6" id="view_employee" hidden>
                    <div class="form-group">
                        <label for="employee">Empleado</label>
                        <select class="custom-select" name="user_id" id="employee">
                        </select>   
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="from">Fecha de inicio</label>
                        <input type="date" id="from" name="from" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="to">Fecha de fin</label>
                        <input type="date" id="to" name="to" class="form-control" required></input>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="today">
                        <label class="custom-control-label" for="today">Hoy</label>
                    </div>   
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="view_type">Eliga la acción a realizar</label>
                        <select class="custom-select" id="view_type" required>
                            <option value="1">Ver</option>
                            <option value="2">Descargar en PDF</option>
                            <option value="3">Descargar en Excel</option>
                        </select>   
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">GENERAR</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script type="application/javascript">
    let reportType;
    let request;
    let method = 'POST'
    let url;
    let viewType;
    function addRequestParams(){
        url = ''
        viewType = parseInt($('#view_type').val());
        reportType = parseInt($('#type').val());
        switch (viewType) {
            case 1:
                url += 'reporte/';
                break;
            case 2:
                url += 'reporte/download/';
                break;
            case 3:
                url += 'reporte/download/excel/'; 
                break;
            default:
                break;
        }
        switch (reportType) {
            case 1:
                url += 'generalReport';
                break;
            case 2:
                url += 'branchOffice';
                break;
            case 3:
                url += 'userReport';
                break;
            case 4:
                method='GET'
                url += 'reportInvent';
                break;
            case 5:
            method='POST'
            url += 'reportInventByBranchOfficeId';
            break;
            default:
                break;
        }
        $('#request').prop('action',url);
        $('#request').prop('method',method);
    }
    $(document).ready(function() {
        $('#today').click(function() {
            let today = new Date();
            let date = today.getFullYear() + "-" + ('0' + (today.getMonth()+1)).slice(-2) + "-" + ('0' + today.getDate()).slice(-2);
            if($(this).is(":checked")){
                $('#from').val(date);
                $('#to').val(date);
                $('#from').prop('readonly',true);
                $('#to').prop('readonly',true);
            }
            else{
                $('#from').prop('readonly',false);
                $('#to').prop('readonly',false);
            }
            
        });
        $('#type').click(function() {
            console.log();
            if($(this).val()==2){
                $('#view_branch').prop('hidden',false);
                $('#view_branch').prop('required',true);
                $('#view_employee').prop('hidden',true);
                $('#view_employee').prop('required',false);
            }
            else if($(this).val()==3){
                getEmployeesByBranch();
                $('#view_branch').prop('hidden',false);
                $('#view_employee').prop('hidden',false);
                $('#view_branch').prop('required',true);
                $('#view_employee').prop('required',true);
            }
            else if($(this).val()==5){
                getEmployeesByBranch();
                $('#view_branch').prop('hidden',false);
                $('#view_branch').prop('required',true);
            }
            else{
                $('#view_branch').prop('required',false);
                $('#view_employee').prop('required',false);
                $('#view_branch').prop('hidden',true);
                $('#view_employee').prop('hidden',true);
            }
            
        });

        $('#branch').change(function (){
            getEmployeesByBranch();
        });

        function getEmployeesByBranch(){
            $.ajax({
                url: "/employeeByOffice/"+($('#branch').val()),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                contentType: "application/json; charset=iso-8859-1",
                data:null,
                dataType: 'html',
                success: function(data) {
                    let employees = JSON.parse(data);
                    ;
                    employees.forEach(employee => {
                        $('#employee').append('<option value="'+employee.id+'">'+employee.name+'</option>');
                    });
                },
                error: function(e) {
                    console.log("ERROR", e);
                },

            });
        }
    }) 
</script>
@endpush