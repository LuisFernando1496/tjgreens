@extends('layouts.app')

@section('content')
<div class="container">
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
                            <label for="ext_number">Total</label>
                            <input readonly class="form-control" type="text" name="total" id="total" placeholder="" required>
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
                <th scope="col">Cantidad</th>
                <th scope="col">Total</th>
                <th scope="col">Usuario Autorizado</th>
                
                <th scope="col">Fecha de compra</th>
            </tr>
        </thead>
        <tbody id="mydata">
            @foreach ($products as $item)
            <tr>
                <th scope="row">{{$item->product->name}}</th>
                <th scope="row">{{$item->quantity}}</th>
                <th scope="row">${{$item->total}}</th>
                <th scope="row">{{$item->user->name}} {{$item->user->last_name}}</th>
                <th scope="row">{{$item->created_at}}</th>
         
            </tr>
            @endforeach
        </tbody>
    </table>    
    {!! $products->render() !!}
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
         

        document.getElementById('name_edit').value = item.name
        document.getElementById('quantity_edit').value = document.getElementById('quantity'+item.id).value
        document.getElementById('total').value = document.getElementById('quantity'+item.id).value*item.cost
        document.getElementById('product_id').value = item.id
       
        
    }
</script>
@endsection