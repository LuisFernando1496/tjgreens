@extends('layouts.app')
@section('content')
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    </head>

<body>
    <div class="my-3 my-md-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-body">
                        @include('expenses.form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
@stop
