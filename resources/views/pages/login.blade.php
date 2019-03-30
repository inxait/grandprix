@extends('layouts.frontpages')

@section('content')
    <div class="page auth">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="form-wrapper">
                        <form id="login-form" class="login">
                            <a href="{{url('/')}}" class="logo">
                                <img src="{{asset('images/logo-grandprix.png')}}"
                                     alt="Grandprix Acdelco" class="img-responsive">
                            </a>
                            <div class="form-group">
                                <p class="slogan">Enciende motores</p>
                            </div>
                            <div class="form-group">
                                <label>Documento</label>
                                <input type="text" name="identification" class="form-control" maxlength="30"
                                      placeholder="Ingresa tu cédula" value="{{ old('identification') }}">
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="Ingresa tu contraseña">
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                      <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Mantenerme conectado
                                    </label>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">
                                Ingresar
                            </button>
                            <div class="form-links border">
                                <a href="{{url('recuperar-cuenta')}}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                            <div class="form-links">
                                <a href="{{url('registro')}}">Registrate aquí</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop
