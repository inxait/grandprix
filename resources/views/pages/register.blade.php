@extends('layouts.frontpages')

@section('content')
    <div class="page auth">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert hidden">
                        <a href="{{url('/')}}" class="logo txt-center">
                            <img src="{{asset('images/logo-grandprix.png')}}"
                                 alt="Grandprix Acdelco" class="img-responsive">
                        </a>
                        Tus datos fueron enviados al área encargada de aprobar el ingreso. <br>
                        Una vez esté aprobado te enviaremos un mail de bienvenida para activación
                        de la cuenta.
                    </div>
                </div>
                <div id="register-col" class="col-md-12">
                    <div class="form-wrapper">
                        <form id="register-form" class="register">
                            <a href="{{url('/')}}" class="logo">
                                <img src="{{asset('images/logo-grandprix.png')}}"
                                     alt="Grandprix Acdelco" class="img-responsive">
                            </a>
                            <div class="form-group">
                                <p class="slogan">Registro</p>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="distributor" class="form-control">
                                            <option value="">-- Selecciona Distribuidor --</option>
                                            @foreach($distributors as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="identification" value="{{old('identification')}}" placeholder="Cédula" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="first_name" value="{{old('first_name')}}" placeholder="Nombres" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="last_name" value="{{old('last_name')}}" placeholder="Apellidos" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <select name="gender" class="form-control" value="{{old('gender')}}">
                                            <option value="">-- Selecciona género --</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" name="email" value="{{old('email')}}" placeholder="Correo electrónico" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="cellphone" value="{{old('cellphone')}}" placeholder="Celular" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <select id="departments-select" value="{{old('department')}}" name="department" class="form-control">
                                            <option value="">-- Selecciona departamento --</option>
                                            @foreach($departments as $department)
                                            <option value="{{$department->id}}">{{$department->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select id="cities-select" name="city" value="{{old('city')}}" class="form-control" disabled>
                                            <option value="">-- Selecciona ciudad --</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="zone" value="{{old('zone')}}" placeholder="Zona" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="address" value="{{old('address')}}" placeholder="Dirección oficina" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btnprimary" type="submit">
                                        Enviar
                                    </button>
                                </div>
                                <div class="form-links">
                                    <a href="{{url('/')}}">Atras</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop
