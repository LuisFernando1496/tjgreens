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
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
            <div class="row">
                <div class="col-md-8 col-lg 8 col-xs-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Detalles de la transferencia</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 col-lg-8 col-xs-12 col-sm-12">
                                    <table class="table table-striped" style="width: 100%;">
                                        <thead class="black white-text">
                                            <tr>
                                                <th scope="col">Código</th>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Marca</th>
                                                <th scope="col">Cantidad traspasada</th>
                                                <th scope="col">subTotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalUsd  = 0;
                                                $totalMxn = 0;
                                            @endphp
                                            @foreach($transfer->products as $item)
                                            <tr>
                                                <td>{{$item->product->bar_code}}</td>
                                                <td>{{$item->product->name}}</td>
                                                <td>{{$item->product->brand->name}}</td>
                                                <td>{{$item->quantity}}</td>
                                                <td>{{$item->total}}</td>
                                            </tr>
                                            @php
                                            $totalUsd += ($item->total/20.68);
                                            $totalMxn += $item->total;
                                        @endphp
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div class="col-md-4 col-xs-12 col-lg-4 col-sm-12">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <strong>Hecho por: </strong> <span>{{$transfer->user->name}} {{$transfer->user->last_name}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            @if($transfer->status == 'Enviado')
                                            <strong>Estatus: </strong> <span style="background-color:#AEEA00; color: white;"><strong>{{$transfer->status}}</strong></span>
                                            @else
                                            <strong>Estatus: </strong> <span style="background-color:#00C853; color: white;"><strong>{{$transfer->status}}</strong></span>
                                            @endif
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Sucursal destino: </strong> <span>{{$transfer->branchOffice->name}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Detalles: </strong> <span>{{$transfer->details}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Total (USD): </strong> <span>${{number_format($totalUsd,2)}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Total (MXN): </strong> <span>${{$totalMxn}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            @if($transfer->destination_branch_office_id == auth()->user()->branch_office_id
                                            && $transfer->status == 'Enviado')
                                            <center>
                                                <td>
                                                    <form action="{{asset('transfers/'.$transfer->id)}}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-outline-success">
                                                            Recibido
                                                        </button>
                                                    </form>
                                                </td>
                                            </center>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop