@extends('layouts.app')

@section('content')


<form action="https://sistematjgreens.com/closeBox/1232" method="POST">
    @csrf
    <button type="submit">Solicitar</button>
</form>
@endsection
