@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard list-liquidations">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <div class="alert alert-warning hidden"></div>
                    <h2>Liquidaciones <small><a href="{{url('dashboard/liquidaciones/crear')}}">Crear liquidación</a></small></h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Porcentaje de ventas</th>
                                    <th>Métrica</th>
                                    <th>Fecha de creación</th>
                                    <th>Fecha de actualización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($liquidations as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->percent_to_give}}</td>
                                    <td>{{$item->measure}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{$item->updated_at}}</td>
                                    <td>
                                        <a href="{{url('dashboard/liquidaciones/'.$item->id.'/calcular')}}" class="btn liquidation-link" data-liquidation>
                                            Calcular liquidación
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
