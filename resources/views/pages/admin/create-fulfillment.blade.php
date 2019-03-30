@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard create-fulfillment">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Crear cumplimiento <small><a href="{{url('dashboard/metricas')}}">Volver</a></small></h2>
                    <form id="create-fulfillment-form" method="POST" enctype="multipart/form-data" action="{{route('create-fulfillment')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="title" class="form-control" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label>Métrica</label>
                                    <select id="measure-select" name="measure" class="form-control">
                                        @foreach($measures as $measure)
                                        <option value="{{$measure->id}}" data-units="{{$measure->units}}">
                                            {{$measure->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Periodo de evaluación</label>
                                    <select name="period" class="form-control">
                                        <option value="Mensual">Mensual</option>
                                        <option value="Trimestral">Trimestral (Q)</option>
                                        <option value="Semestral">Semestral</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Meta de cumplimiento</label>
                                    <div class="input-group">
                                        <input type="text" name="goal" class="amount form-control" maxlength="30">
                                        @foreach($measures as $measure)
                                        @if ($loop->first)
                                        <div class="measure-addon input-group-addon">{{$measure->units}}</div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Recompensa</label>
                                    <div class="input-group">
                                        <input type="text" name="reward" class="form-control" maxlength="30">
                                        <div class="input-group-addon">KM</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Valor de sobrecumplimiento</label>
                                    <div class="input-group">
                                        <input type="text" name="overcompliance_value" class="amount form-control" maxlength="30">
                                        @foreach($measures as $measure)
                                        @if ($loop->first)
                                        <div class="measure-addon input-group-addon">{{$measure->units}}</div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Recompensa de sobrecumplimiento</label>
                                    <div class="input-group">
                                        <input type="text" name="overcompliance_reward" class="form-control" maxlength="30">
                                        <div class="input-group-addon">KM</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-6">
                                <button type="submit" class="btn btn-primary">Crear</button>
                            </div>
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
