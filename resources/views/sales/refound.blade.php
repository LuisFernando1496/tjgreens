@extends('layouts.app')

@section('content')
<div class="my-3 my-md-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-header">
                    <h5>Detalles de venta devuelta</h5>
                </div>
                <div class="card-body">
                    <center>
                        <table class="display table table-striped table-bordered" id="example" style="width:100%; padding-top: 5%;">
                            <thead>
                                <tr>
                                    <th class="text-center">Usuario autorizado</th>
                                    <th class="text-center">Descuento</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Fecha de devolución</th>
                                    <th class="text-center"></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale as $item)
                                <tr>
                                    <td class="text-center">{{$item->user->name}} {{$item->user->last_name}}</td>
                                    <td class="text-center">{{$item->discount}}</td>
                                    <td class="text-center">{{$item->cart_subtotal}}</td>
                                    <td class="text-center">{{$item->cart_total}}</td>
                                    <td class="text-center">{{$item->updated_at}}</td>
                                    <td class="text-center">
                                        <a href="{{asset('sale-detail/'.$item->id.'')}}" class="btn btn-primary btn-sm mx-2"><small>DETALLES</small></a>
                                    </td>

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
@stop