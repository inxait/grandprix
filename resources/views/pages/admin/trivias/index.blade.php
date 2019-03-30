@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard list-news">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Trivias <small><a href="{{route('trivias.create')}}">Crear trivia</a></small></h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Fecha de Inicio</th>
                                    <th>Fecha de finalización</th>
                                    <th>Total puntos</th>
                                    <th>Activa</th>
                                    <th>Entregar puntos por porcentaje</th>
                                    <th>Fecha de creación</th>
                                    <th>Fecha de actualización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trivias as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->start_date}}</td>
                                    <td>{{$item->finish_date}}</td>
                                    <td>{{$item->total_val}}</td>
                                    <td>{{($item->active)? 'Si' : 'No'}}</td>
                                    <td>{{($item->allow_percent_points)? 'Si' : 'No'}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{$item->updated_at}}</td>
                                    <td>
                                        <a href="{{url('dashboard/trivias/'.$item->id.'/toggle')}}">
                                            {{($item->active) ? 'Desactivar' : 'Activar'}}
                                        </a> <br>
                                        <a href="{{url('dashboard/trivias/'.$item->id.'/editar')}}">
                                            Editar
                                        </a> <br>
                                        <a href="{{url('dashboard/trivias/'.$item->id.'/participantes')}}">
                                            Descargar participación
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$trivias->links()}}
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
