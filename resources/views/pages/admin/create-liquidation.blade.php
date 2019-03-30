@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard create-liquidation">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Crear liquidación <small><a href="{{url('dashboard/liquidaciones')}}">Volver</a></small></h2>
                    <form method="POST" action="{{route('create-liquidation')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="liquidation_name" class="form-control" maxlength="90">
                                </div>
                                <div class="form-group">
                                    <label>Mes a liquidar</label>
                                    <select name="month" class="form-control">
                                    @foreach($months as $month)
                                    <option value="{{$loop->iteration}}">{{ucfirst($month)}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Porcentaje de entrega sobre ventas</label>
                                    <div class="input-group">
                                        <input type="text" name="liquidation_percent" class="form-control" maxlength="20">
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Métrica relacionada</label>
                                    <select name="measure_id" class="form-control">
                                        @foreach ($measures as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-12">
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

