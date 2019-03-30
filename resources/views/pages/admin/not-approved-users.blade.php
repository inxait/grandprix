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
                    <h2>Usuarios pendientes de aprobación</h2>
                    <a href="#" data-link="{{url('users/approve')}}"
                       class="link-approve" data-toggle="modal" data-target="#approveModal">
                        Aprobar todos
                    </a>
                    <form class="navbar-form navbar-left pull-right" role="search" method="GET" action="{{url('dashboard/pendientes')}}">
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
                                <th>Celular</th>
                                <th>Dirección</th>
                                <th>Zona</th>
                                <th>Dirección Ip</th>
                                <th>Fecha de creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($notApproved) > 0)
                            @foreach($notApproved as $item)
                            <tr>
                                <td>{{$item->first_name}}</td>
                                <td>{{$item->last_name}}</td>
                                <td>{{$item->identification}}</td>
                                <td>{{$item->gender}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->cellphone}}</td>
                                <td>{{$item->address}}</td>
                                <td>{{$item->zone}}</td>
                                <td>{{$item->ip_address}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>
                                    <a href="#" data-link="{{url('users/'.$item->id.'/approve')}}"
                                       class="link-approve" data-toggle="modal" data-target="#approveModal">
                                        Aprobar
                                    </a>
                                    <a href="#" data-link="{{url('users/'.$item->id.'/delete')}}"
                                       class="link-confirmation" data-toggle="modal" data-target="#confirmModal">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="11" class="alert alert-warning"><center>No existen registros.</center></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    {{$notApproved->links()}}
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
