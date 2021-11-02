@extends('layouts.app')
@section('content')

<div class="container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div style="text-align:right; padding-bottom: 2%;">
        <a href="transfers/create" class="btn btn-outline-primary">Nuevo traspaso</a>
    </div>
    @include('tranfers.data')
</div>

@endsection