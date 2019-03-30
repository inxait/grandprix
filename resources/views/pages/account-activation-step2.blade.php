@extends('layouts.frontpages')

@section('content')
    @include('partials.header')

    <div class="page account-activation">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="welcome">
                        <img src="{{asset('images/welcome-msg.png')}}"
                             alt="Bienvenido a Grand Prix" class="img-responsive">
                    </div>
                    <div class="character">
                        <div class="helmet">
                            <img src="{{asset('images/avatar/helmet/'.$avatar->helmet.'.png')}}">
                        </div>
                        <div class="uniform">
                            <img src="{{asset('images/avatar/uniform/'.$avatar->uniform.'.png')}}">
                        </div>
                        <div class="gloves">
                            <img src="{{asset('images/avatar/gloves/'.$avatar->gloves.'.png')}}">
                        </div>
                        <div class="shoes">
                            <img src="{{asset('images/avatar/shoes/'.$avatar->shoes.'.png')}}">
                        </div>
                    </div>
                    <div class="name-info">
                        <div class="user-name">{{Auth::user()->first_name.' '.Auth::user()->last_name}}</div>
                        <div class="info">Arma tu personaje</div>
                    </div>
                    <div class="step2-instructions">
                        <p>
                            El segundo paso es asigna una contraseña a tu perfil
                        </p>
                        <form method="POST" action="{{ url('account/update2') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Contraseña">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmar contraseña">
                            </div>
                            <div class="form-group">
                                <a href="{{asset('Terminos_y_Condiciones_Grand Prix_ACDelco.pdf')}}"
                                   target="_blank" rel="noopener noreferrer" class="terms">
                                    Leer términos y condiciones
                                </a>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                      <input type="checkbox" name="terms"> Aceptar términos y condiciones
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-next">Activar cuenta</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop

@section('scripts')
<script>
@if ($errors->any())
    @foreach ($errors->all() as $error)
        humane.error("{{$error}}");
    @endforeach
@endif
</script>
@stop
