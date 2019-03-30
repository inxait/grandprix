@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard update-points">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Formato actualización de puntos</h2>
                            <a href="{{asset('formats/formato_puntos.xlsx')}}">Descargar formato</a>
                        </div>
                        <div class="col-md-6">
                            <h2>Actualizar puntos <small><a href="{{url('dashboard')}}">Volver</a></small></h2>
                            <form method="POST" enctype="multipart/form-data"
                                  action="{{route('update-points')}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>Excel de puntos</label>
                                    <input type="file" name="points_excel" class="form-control">
                                </div>
                                <div class="form-group hidden">
                                    <div class="alert alert-warning" role="alert">
                                        <strong>Procesando.</strong> Esta operación puede tardar varios minutos.
                                    </div>
                                </div>
                                <button id="btn-points-excel" type="submit" class="btn btn-primary">
                                    Actualizar
                                </button>
                            </form>
                        </div>
                    </div>
                    <h2>Historial de puntos</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre del evento</th>
                                    <th>Mes</th>
                                    <th>Año</th>
                                    <th>Valor</th>
                                    <th>Tipo de puntos</th>
                                    <th>Usuario</th>
                                    <th>Fecha de creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($points as $item)
                                <tr>
                                    <td>{{$item->points_event}}</td>
                                    <td>{{ucfirst($item->month)}}</td>
                                    <td>{{$item->year}}</td>
                                    <td>{{$item->value.' KM'}}</td>
                                    <td>{{Points::getType($item->type)}}</td>
                                    <td>{{$item->user_identification}}</td>
                                    <td>{{$item->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$points->links()}}
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
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
