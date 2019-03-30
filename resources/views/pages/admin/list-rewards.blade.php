@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard list-rewards">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Premios <small><a href="{{url('dashboard/premios/crear')}}">Crear premio</a></small></h2>
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Imagen</th>
                                    <th>Descripción</th>
                                    <th>Fecha de creación</th>
                                    <th>Fecha de actualización</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($rewards) > 0)
                                @foreach($rewards as $item)
                                <tr>
                                    <td>{{$item->title}}</td>
                                    <td>
                                        <img src="{{asset('storage/'.$item->image)}}" class="img-responsive" width="200">
                                    </td>
                                    <td>{{$item->description}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{$item->updated_at}}</td>
                                    <td>{{ ($item->active != 1) ? 'Inactivo' : 'Activo' }}</td>
                                    <td>
                                        <a href="{{url('dashboard/premios/estado/'.$item->id)}}">{{ ($item->active != 1) ? 'Activar' : 'Desactivar' }}</a>
                                        <a href="{{route('dashboard.edit-rewards', $item->id)}}">Editar</a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" class="alert alert-warning"><center>No hay registros.</center></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        {{$rewards->links()}}
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop

@if (session('status'))
@section('scripts')
    <script>
    humane.info = humane.spawn({
      addnCls: 'humane-flatty-success',
      timeout: 3500
    });

    humane.info("{{ session('status') }}");
    </script>
@stop
@endif
