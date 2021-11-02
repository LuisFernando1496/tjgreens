@extends('layouts.app')

@section('content')
    <div class="my-3 my-md-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if($sale->status_credit=='adeudo')
                        <div style="text-align:right">
                            <button onclick="limpiar()" type="button" class="btn btn-outline-primary my-2" data-toggle="modal" data-target="#userModal"><small>ABONAR</small></button>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="card-header text-uppercase">Detalles de venta </div>

                        <center>
                        <table class="display table table-striped table-bordered"  style="width:100%">
                        <tfoot>
                            <tr>
                            <td colspan="1"></td>
                            <td colspan="1"><b>Descuento en venta: </b>{{$sale->discount}}%</td>
                            <td colspan="1"></td>
                            <td colspan="1"></td>
                            <td colspan="1"></td>
                            </tr>
                            <tr>
                            <td colspan="1"></td>
                            <td colspan="1"><b>Descuento total: </b>${{$sale->amount_discount}}</td>
                            <td colspan="1"></td>
                            <td colspan="1"></td>
                            <td colspan="1"><b>Total: </b>${{$sale->cart_total}}</td>
                            </tr>
                        </tfoot>
                            <thead>
                            <tr>
                                <th class="text-center">Nombre producto/servicio</th>
                                <th class="text-center" >Descuento</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Precio</th>
                                <th class="text-center">Subtotal</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($details as $item)
                                <tr>
                                    <td class="text-center">{{$item->name}}</td>
                                    <td class="text-center">{{$item->discount}}%</td>
                                    <td class="text-center">{{$item->quantity}}</td>
                                    <td class="text-center">${{$item->sale_price}} c/u</td>
                                    <td class="text-center">${{$item->subtotal}}</td>                                    
                                </tr>
                                
                            @endforeach
                                
                            </tbody>
                        </table>

                        </center>
                     
                        
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-body">
                        <div class="card-header text-uppercase">Historial de pago</div>
                        <center>
                        <table class="display table table-striped table-bordered"  style="width:100%">
                        
                            <thead>
                            <tr>
                                <th class="text-center">Nombre del cliente</th>
                                <th class="text-center" >Deposito</th>
                                <th class="text-center">Restante</th>
                                

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($history as $item)
                                <tr>
                                    <td class="text-center">{{$item->sale->client->name}} {{$item->sale->client->last_name}}</td>
                                    <td class="text-center">${{$item->deposit}}</td>
                                    <td class="text-center">${{$item->leftover}}</td>
                                                                       
                                </tr>
                                
                            @endforeach
                                
                            </tbody>
                        </table>

                        </center>
                     
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">ABONAR A CREDITO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myForm" action="/abonar" method="post" onsubmit="upperCreate()">
                        @csrf                        
                        <div class="form-group my-3 mx-3">
                            <label for="name">CANTIDAD A ABONAR</label>
                            <input type="number" class="form-control" name="deposit" id="deposit" value=""placeholder="$0" required />
                        </div>  
                            <input type="hidden" class="form-control" name="leftover" id="leftover"/>
                            <input type="hidden" class="form-control" name="sale_id" id="sale_id" value="{{$sale->id}}"/>
                            <input type="hidden" class="form-control" name="total_venta" id="total_venta" value="{{$sale->cart_total}}"/>
                            <input type="hidden" class="form-control" name="client_id" id="client_id" value="{{$sale->client_id}}"/>
                            @if($last_payment==null)
                            <input type="hidden" class="form-control" name="last_payment" id="last_payment" value="{{$sale->cart_total}}"/>
                            @else
                            <input type="hidden" class="form-control" name="last_payment" id="last_payment" value="{{$last_payment->leftover}}"/>
                            @endif
                        <div class="form-group my-3 mx-3">
              
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                       
                    </form>
                </div>
                
            </div>
        </div>
    </div>
@stop
@push('scripts')

<script type="application/javascript">
$(document).ready(function() {
    $('#deposit').change( function() {
        
        var efectivo = parseInt(document.getElementById("deposit").value, 10);
        var total_pagar = parseInt(document.getElementById("total_venta").value, 10);
        var ultimo_pago = parseInt(document.getElementById("last_payment").value, 10);

        if(ultimo_pago==""){
            if(total_pagar-efectivo<0){
                alert('Lo máximo para abonar es: $'+total_pagar);
                $('#leftover').val(0);
                $('#deposit').val(total_pagar);
            }else{
                $('#leftover').val(total_pagar-efectivo);
            }

        }else{
            if(ultimo_pago-efectivo<0){
                alert('Lo máximo para abonar es: $'+ultimo_pago);
                $('#leftover').val(0);
                $('#deposit').val(ultimo_pago);
            }else{
                $('#leftover').val(ultimo_pago-efectivo);
            }

        }
        
        
        });  
       
    });
    
</script>
@endpush('scripts')