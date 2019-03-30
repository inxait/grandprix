@extends('layouts.frontpages')

@section('content')
    <div class="page auth">
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="logo">
                        <a href="{{url('/')}}">
                            <img src="{{asset('images/logo-grandprix.png')}}"
                                 alt="Grandprix Acdelco" class="img-responsive">
                        </a>
                    </div>
                    <div class="alert alert-info">
                        Estamos en mantenimiento para ofrecerte una mejor experiencia. <br>
                        Volveremos en un momento.
                    </div>
                    <div class="logo-acdelco">
                        <img src="{{asset('images/logo-acdelco.png')}}" alt="Acdelco" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop
