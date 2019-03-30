@extends('layouts.frontpages')

@section('content')
    @include('partials.header')
    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>{{ $exception->getMessage() }}</h2>
                    <a href="{{url('/')}}">Volver</a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop
