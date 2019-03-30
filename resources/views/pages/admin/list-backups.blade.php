@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard list-backups">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Backups <small><a href="{{url('backups/create')}}">Crear backup</a></small></h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre del archivo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $item)
                                <tr>
                                    <td><a href="{{url('dashboard/backups/download/'.$item)}}">{{$item}}</a></td>
                                    <td>
                                        <a href="{{url('dashboard/backups/delete/'.$item)}}">
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
