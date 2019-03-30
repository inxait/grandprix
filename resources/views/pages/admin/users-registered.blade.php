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
                <div class="row">
                    <h2>Usuarios registrados</h2>
                    <a href="{{url('users/active/download')}}">Descargar</a>
                    <form class="navbar-form navbar-left pull-right" role="search" method="GET" action="{{url('dashboard/registrados')}}">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Nombres, Documento" name="palabra" id="palabra" value="{{old('palabra')}}">
                        </div>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Identificación</th>
                                <th>Género</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Fecha de creación</th>
                                <th>Fecha de actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($registered) > 0)
                            @foreach($registered as $item)
                            <tr>
                                <td>{{$item->first_name}}</td>
                                <td>{{$item->last_name}}</td>
                                <td>{{$item->identification}}</td>
                                <td>{{ucfirst($item->gender)}}</td>
                                <td>{{$item->email}}</td>
                                <td>
                                    @foreach($item->roles()->get() as $role)
                                    {{$role->name}}
                                    @endforeach
                                </td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->updated_at}}</td>
                                <td>
                                    @foreach($item->roles()->get() as $role)
                                    @if($role->name == 'seller')
                                    <a href="#" data-link="{{url('users/'.$item->id.'/delete')}}" class="link-confirmation" data-toggle="modal" data-target="#confirmModal">Eliminar</a>
                                    <a href="{{url('dashboard/'.$item->id.'/show-user/reg')}}">Editar</a>
                                    @endif
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" class="alert alert-warning"><center>No existen registros.</center><td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    {{$registered->links()}}
                </div>
            </div>
        </div>
    </div>
    @include('partials.admin.approve-modal')
    @include('partials.admin.confirmation-modal', [
    'description' => '¿Está seguro de eliminar el usuario? No se podrá revertir la acción.'
    ])
</div>
@include('partials.footer')
@stop

@section('scripts')
<script>
    @if (session('status'))
            humane.info = humane.spawn({
                addnCls: 'humane-flatty-success',
                timeout: 3500
            });

    humane.info("{{ session('status') }}");
    @endif

            @if ($errors->any())
            @foreach ($errors->all() as $error)
    humane.error("{{$error}}");
    @endforeach
            @endif
</script>
@stop
