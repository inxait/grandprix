@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard metrics">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Métricas</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Unidades</th>
                                    <th>Tipo</th>
                                    <th>Fecha de creación</th>
                                    <th>Fecha de actualización</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($measures as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->units}}</td>
                                    <td>{{$item->type}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{$item->updated_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Formato medidas de cumplimiento</h2>
                            <a href="{{asset('formats/formato_cumplimientos.xlsx')}}">Descargar formato</a>
                        </div>
                        <div class="col-md-6">
                            <h2>Cargar medidas de cumplimiento</h2>
                            <form method="POST" enctype="multipart/form-data" action="{{route('upload-fulfillment')}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <select name="month" class="form-control">
                                    @foreach($months as $month)
                                    <option value="{{$loop->iteration}}">{{ucfirst($month)}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Excel de cumplimientos</label>
                                    <input type="file" name="fulfillments_excel" class="form-control">
                                </div>
                                <div class="form-group hidden">
                                    <div class="alert alert-warning" role="alert">
                                        <strong>Procesando.</strong> Esta operación puede tardar varios minutos.
                                    </div>
                                </div>
                                <button id="btn-users-excel" type="submit" class="btn btn-primary">Cargar</button>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <h2>
                        Medidas de cumplimiento
                        <small>Mostrando {{$totalFulfillments[0]->total}} registros</small>
                    </h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Activa</th>
                                    <th>Meta</th>
                                    <th>Recompensa</th>
                                    <th>Valor de sobrecumplimiento</th>
                                    <th>Recompensa de sobrecumplimiento</th>
                                    <th>Periodo de evaluación</th>
                                    <th>Mes de evaluación</th>
                                    <th>Fecha de creación</th>
                                    <th>Pertenece a métrica:</th>
                                    <th>Pertenece a usuario:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fulfillments as $item)
                                <tr>
                                    <td>{{$item->title}}</td>
                                    <td>{{($item->active) ? 'Si' : 'No'}}</td>
                                    <td>{{number_format($item->goal)}}</td>
                                    <td>{{$item->reward}}</td>
                                    <td>{{number_format($item->overcompliance_value)}}</td>
                                    <td>{{$item->overcompliance_reward}}</td>
                                    <td>{{$item->period}}</td>
                                    <td>{{ucfirst(Calendar::getHumanMonth($item->month))}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        @foreach($measures as $measure)
                                        @if($measure->id == $item->measure_id)
                                        {{$measure->name}}
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        {{$item->user}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$fulfillments->links()}}
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
