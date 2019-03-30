@extends('layouts.frontpages')

@section('content')
    <div class="page auth">
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="form-wrapper">
                        <form id="recover-form" class="login">
                            <a href="{{url('/')}}" class="logo">
                                <img src="{{asset('images/logo-grandprix.png')}}"
                                     alt="Grandprix Acdelco" class="img-responsive">
                            </a>
                            <p class="slogan">Recupera tu contraseña</p>
                            <div class="form-group">
                                <input type="email" class="form-control"
                                       name="email" placeholder="Correo electrónico" value="{{ old('email') }}">
                            </div>
                            <button class="btn btn-primary" type="submit">
                                Enviar correo
                            </button>
                            <div class="form-links">
                                <a href="{{url('/')}}">Atras</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@endsection
