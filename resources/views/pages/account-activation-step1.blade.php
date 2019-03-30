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
                            <img src="{{asset('images/avatar/helmet/blue.png')}}">
                        </div>
                        <div class="uniform">
                            <img src="{{asset('images/avatar/uniform/blue.png')}}">
                        </div>
                        <div class="gloves">
                            <img src="{{asset('images/avatar/gloves/blue.png')}}">
                        </div>
                        <div class="shoes">
                            <img src="{{asset('images/avatar/shoes/blue.png')}}">
                        </div>
                    </div>
                    <div class="name-info">
                        <div class="user-name">{{Auth::user()->first_name.' '.Auth::user()->last_name}}</div>
                        <div class="info">Arma tu personaje</div>
                    </div>
                    <div class="step1-instructions">
                        <p>
                            El primer paso es ponerte tu uniforme y estás listo para
                            el arranque de la carrera:
                        </p>
                        <form method="POST" action="{{ url('account/update1') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="text" class="form-control" maxlength="90"
                                       name="surname" placeholder="Ingresa tu sobre nombre">
                            </div>
                            <div class="form-group">
                                <label>Opciones de casco</label> <br>
                                <div class="select-item">
                                    <input id="helmet1" class="select-radio" type="radio" value="blue" name="helmet_color" checked/>
                                    <label for="helmet1"><span><img src="{{asset('images/avatar/helmet/blue.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="helmet2" class="select-radio" type="radio" value="green" name="helmet_color"/>
                                    <label for="helmet2"><span><img src="{{asset('images/avatar/helmet/green.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="helmet3" class="select-radio" type="radio" value="orange" name="helmet_color"/>
                                    <label for="helmet3"><span><img src="{{asset('images/avatar/helmet/orange.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="helmet4" class="select-radio" type="radio" value="purple" name="helmet_color"/>
                                    <label for="helmet4"><span><img src="{{asset('images/avatar/helmet/purple.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="helmet5" class="select-radio" type="radio" value="red" name="helmet_color"/>
                                    <label for="helmet5"><span><img src="{{asset('images/avatar/helmet/red.png')}}"></span></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Uniforme</label> <br>
                                <div class="select-item">
                                    <input id="uniform1" class="select-radio" type="radio" value="blue" name="uniform_color" checked/>
                                    <label for="uniform1"><span><img src="{{asset('images/avatar/select/blue.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="uniform2" class="select-radio" type="radio" value="green" name="uniform_color"/>
                                    <label for="uniform2"><span><img src="{{asset('images/avatar/select/green.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="uniform3" class="select-radio" type="radio" value="orange" name="uniform_color"/>
                                    <label for="uniform3"><span><img src="{{asset('images/avatar/select/orange.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="uniform4" class="select-radio" type="radio" value="red" name="uniform_color"/>
                                    <label for="uniform4"><span><img src="{{asset('images/avatar/select/red.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="uniform5" class="select-radio" type="radio" value="yellow" name="uniform_color"/>
                                    <label for="uniform5"><span><img src="{{asset('images/avatar/select/yellow.png')}}"></span></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Guantes</label> <br>
                                <div class="select-item">
                                    <input id="gloves1" class="select-radio" type="radio" value="blue" name="gloves_color" checked/>
                                    <label for="gloves1"><span><img src="{{asset('images/avatar/select/blue.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="gloves2" class="select-radio" type="radio" value="green" name="gloves_color"/>
                                    <label for="gloves2"><span><img src="{{asset('images/avatar/select/green.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="gloves3" class="select-radio" type="radio" value="purple" name="gloves_color"/>
                                    <label for="gloves3"><span><img src="{{asset('images/avatar/select/purple.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="gloves4" class="select-radio" type="radio" value="red" name="gloves_color"/>
                                    <label for="gloves4"><span><img src="{{asset('images/avatar/select/red.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="gloves5" class="select-radio" type="radio" value="yellow" name="gloves_color"/>
                                    <label for="gloves5"><span><img src="{{asset('images/avatar/select/yellow.png')}}"></span></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Zapatos</label> <br>
                                <div class="select-item">
                                    <input id="shoes1" class="select-radio" type="radio" value="blue" name="shoes_color" checked/>
                                    <label for="shoes1"><span><img src="{{asset('images/avatar/select/blue.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="shoes2" class="select-radio" type="radio" value="green" name="shoes_color"/>
                                    <label for="shoes2"><span><img src="{{asset('images/avatar/select/green.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="shoes3" class="select-radio" type="radio" value="orange" name="shoes_color"/>
                                    <label for="shoes3"><span><img src="{{asset('images/avatar/select/orange.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="shoes4" class="select-radio" type="radio" value="red" name="shoes_color"/>
                                    <label for="shoes4"><span><img src="{{asset('images/avatar/select/red.png')}}"></span></label>
                                </div>
                                <div class="select-item">
                                    <input id="shoes5" class="select-radio" type="radio" value="yellow" name="shoes_color"/>
                                    <label for="shoes5"><span><img src="{{asset('images/avatar/select/yellow.png')}}"></span></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-next">Siguiente</button>
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
