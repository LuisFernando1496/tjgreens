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
    <!-- Edit Modal -->
    <div class="modal fade" id="productModalEdit" tabindex="-1" role="dialog" aria-labelledby="productModalEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalEditLabel">Detalles de la compra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myFormEdit" action="/purchase" method="post">
                        @csrf
                        <div class="form-group  my-3 mx-3">
                            <label for="name">Nombre del producto</label>
                            <input class="form-control" type="text" name="name" id="name_edit" placeholder="Nombre" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="street">Cantidad</label>
                            <input readonly class="form-control" type="text" name="quantity" id="quantity_edit" placeholder="" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="ext_number">Total (MXN)</label>
                            <input readonly class="form-control" type="text" name="total" id="total" placeholder="" required>
                        </div>
                        <div class="form-group my-3 mx-3">
                            <label for="ext_number">Total (USD)</label>
                            <input readonly class="form-control" type="text" name="totalUsd" id="totalUsd" placeholder="" required>
                        </div>
                        <input type="hidden" name="product_id" id="product_id" value="">  
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-outline-primary">Confirmar compra</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>

    <table class="display table table-striped table-bordered" id="example" style="font-size:0.85em; width:100%">
        <thead class="black white-text">
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Categoria</th>
                <th scope="col">Stock</th>
                <!--<th scope="col">Costo pza (MXN)</th>-->
                <th scope="col">Costo pza (USD)</th>
                <th scope="col">Cantidad</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="mydata">
            @foreach ($products as $item)
            <tr>
                <th scope="row">{{$item->name}}</th>
                <th scope="row">{{$item->category->name}}</th>
                <th scope="row">{{$item->stock}}</th>
                <!--<th scope="row">{{$item->cost}}</th>-->
                <th scope="row">{{number_format(($item->cost),2)}}</th>
                <!--<th scope="row">{{number_format(($item->cost /20.68),2)}}</th>-->
                <th scope="row"><input type="number" name="" id="quantity{{$item->id}}"></th>
                <td>                    
                    <button onclick="llenar({{$item}})" type="button" class="btn btn-outline-primary btn-sm"Sdata-type="edit" data-toggle="modal" data-target="#productModalEdit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                            <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                            <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z"/>
                        </svg>
                            Comprar
                        </button> 
                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <form action="{{route('reportPurchase')}}" method="POST">
        @csrf  
        <h5>Establecer fecha pagara generar reporte de compras</h5>
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
                               <input type="date" id="to" name="to" class="form-control" >
                           </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col">
                           <div class="custom-control custom-switch">
                               <input type="checkbox" class="custom-control-input" id="today" name="today">
                               <label class="custom-control-label" for="today">Hoy</label>
                           </div>   
                       </div>
                   </div>
    <button type="submit" >Decargar reporte de compras</button>
    </form>
    
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
         
      //const costUsd = (item.cost/20.68).toFixed(2)
        document.getElementById('name_edit').value = item.name
        document.getElementById('quantity_edit').value = document.getElementById('quantity'+item.id).value
        document.getElementById('total').value = document.getElementById('quantity'+item.id).value*item.cost
        document.getElementById('totalUsd').value = document.getElementById('quantity'+item.id).value//*costUsd
        document.getElementById('product_id').value = item.id
       
        
    }
</script>
@endsection