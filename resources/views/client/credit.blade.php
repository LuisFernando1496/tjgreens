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

        <table class="display table table-striped table-bordered" id="example" style="width:100%">
            <thead class="black white-text">
                <tr>
                    <th scope="col">Cliente</th>
                    <th scope="col">Total</th>
                    <th scope="col">Abonos</th>
                    {{-- solo si es admin --}}
                   
                    <th scope="col">Restante</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Estatus</th>                
           
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody id="mydata">
                @foreach ($credit as $item)
                    @php
                        $total = 0;
                        $restante = $item->cart_total;
                    @endphp
                <tr>
                    <th scope="row">{{$item->client->name}}</th>
                    <th>${{$item->cart_total}}</th>
                    <td>
                        @foreach ($item->payments as $item2)
                            @php
                                $total += $item2->deposit;
                                $restante=$item->cart_total-$total;
                            @endphp
                        @endforeach
                        ${{$total}}.00
                    </td>
                    <td>${{$restante}}</td>
                    <td>{{$item->created_at}}</td>
                    @if($item->status_credit=='adeudo')
                    <td><div class="card" style="text-align:center; background-color:red">
                    <label style="color:white" for="status" >{{$item->status_credit}}</label>
                    </div>
                    </td>
                    @else
                    <td><div class="card" style="text-align:center; background-color:green">
                    <label style="color:white" for="status" >{{$item->status_credit}}</label>
                    </div>
                    </td>
                    @endif
                    <td>                   
                        <div class="row">
                        @if (Auth::user()->rol_id == 1)

                                    <form onsubmit="return confirm('Cancelar esta venta?')" action="/sale/{{$item->id}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-outline-danger btn-sm  mx-2" data-type="delete">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                                            </svg>   
                                            <small>CANCELAR</small>
                                        </button> 
                                    </form>
                                @endif
                        </div>                                                                     
                    </td>
                    <td>
                    <a href="{{asset('sale-detail-history/'.$item->id.'')}}" class="btn btn-primary btn-sm mx-2"><small>DETALLES</small></a>
                    </td>
                </tr>
        
                @endforeach
            </tbody>
        </table>   
        
</div>
@endsection