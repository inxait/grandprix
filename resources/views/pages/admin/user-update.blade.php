@extends('layouts.adminpages')

@section('content')
@include('partials.header')

<div class="page dashboard users">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-2">
                @include('partials.admin.side-menu')
            </div>
            <div class="col-md-8">
                <h2>Editar Usuario
                    <small><a href="{{url('dashboard/registrados')}}">Volver</a></small>
                </h2>
                <form action="{{url('/dashboard/'.$data['user']->id.'/update-user/'.$data['tipo'])}}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-10">
                            <input type="hidden" name="user" id="user" value="{{$data['user']->id}}">
                            <div class="form-group">
                                <label>Nombres</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" maxlength="80" value="{{(is_null($data['user']->first_name) ? old('first_name') : $data['user']->first_name)}}" required>
                            </div>
                            <div class="form-group">
                                <label>Apellidos</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" maxlength="80" value="{{(is_null($data['user']->last_name) ? old('last_name') : $data['user']->last_name)}}" required>
                            </div>
                            <div class="form-group">
                                <label>Identificación</label>
                                <input type="text" name="identification" id="identification" class="form-control" maxlength="80" value="{{(is_null($data['user']->identification) ? old('identification') : $data['user']->identification)}}" required>
                            </div>
                            <div class="form-group">
                                <label>Género</label>
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    <option value="Masculino" {{($data['user']->gender == 'Masculino') ? 'selected' : ''}}>Masculino</option>
                                    <option value="Femenino" {{($data['user']->gender == 'Femenino') ? 'selected' : ''}}>Femenino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Correo</label>
                                <input type="email" name="email" id="email" class="form-control" maxlength="80" value="{{(is_null($data['user']->email) ? old('email') : $data['user']->email)}}" required>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('partials.footer')
@stop

@section('scripts')
<script>
    @if ($errors->any())
            @foreach($errors->all() as $error)
    humane.error("{{$error}}");
    @endforeach
            @endif
</script>
@stop
