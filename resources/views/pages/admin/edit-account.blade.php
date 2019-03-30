@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard edit-account">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <div class="col-md-12">
                        <h2>Editar cuenta
                            <small><a href="{{url('dashboard')}}">Volver</a></small>
                        </h2>
                    </div>
                    <form id="update-account-form" method="POST" action="{{route('update-account')}}">
                        {{ csrf_field() }}
                        <input type="hidden" value="{{$user->id}}" name="id">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label> <br>
                                <input type="text" class="form-control" name="first_name"
                                       value="{{(is_null($user->first_name)) ? old('first_name') : $user->first_name}}">
                            </div>
                            <div class="form-group">
                                <label>Apodo</label> <br>
                                <input type="text" class="form-control" name="surname"
                                       value="{{(is_null($user->surname)) ? old('surname') : $user->surname}}">
                            </div>
                            <div class="form-group">
                                <label>Correo electrónico</label> <br>
                                <input type="email" class="form-control" name="email"
                                       value="{{(is_null($user->email)) ? old('email') : $user->email}}">
                            </div>
                            <div class="form-group">
                                <label>Departamento</label>
                                <select id="departments-select" value="{{old('department')}}"
                                        name="department" class="form-control">
                                    <option value="">-- Selecciona departamento --</option>
                                    @foreach($departments as $department)
                                    <option @if(!is_null($city))
                                    @if($department->id == $city->department->id) selected @endif
                                    @endif value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Dirección</label> <br>
                                <input type="text" class="form-control" name="address"
                                       value="{{(is_null($user->address)) ? old('address') : $user->address}}">
                            </div>
                            <div class="form-group">
                                <label>Cambiar contraseña</label> <br>
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Apellido</label> <br>
                                <input type="text" class="form-control" name="last_name"
                                       value="{{(is_null($user->last_name)) ? old('last_name') : $user->last_name}}">
                            </div>
                            <div class="form-group">
                                <label>Cédula</label> <br>
                                <input type="text" class="form-control" name="identification"
                                       value="{{$user->identification}}" readonly="true">
                            </div>
                            <div class="form-group">
                                <label>Teléfono</label> <br>
                                <input type="text" class="form-control" name="cellphone"
                                       value="{{$user->cellphone}}">
                            </div>
                            <div class="form-group">
                                <label>Ciudad</label> <br>
                                <select id="cities-select" name="city"
                                        value="{{(is_null($city)) ? old('city') : $city->id}}"
                                class="form-control" @if(is_null($city)) disabled @endif>
                                    @if(is_null($city))
                                    <option value="">-- Selecciona ciudad --</option>
                                    @else
                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Zona</label> <br>
                                <input type="text" class="form-control" name="zone"
                                       value="{{(is_null($user->zone)) ? old('zone') : $user->zone}}">
                            </div>
                            <div class="form-group">
                                <label>Confirmar cambiar contraseña</label> <br>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-1"></div>
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
