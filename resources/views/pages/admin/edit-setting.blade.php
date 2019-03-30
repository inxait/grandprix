@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard edit-settings">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Editar configuración <small><a href="{{url('dashboard/configuraciones')}}">Volver</a></small></h2>
                    <form method="POST" action="{{route('update-setting')}}">
                        <div class="col-md-12">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{$setting->id}}" name="id">
                            <input type="hidden" value="{{$setting->type}}" name="type">
                            <div class="form-group">
                                <label>Título</label> <br>
                                <input type="text" value="{{$setting->title}}"
                                       readonly="true" class="form-control">
                            </div>
                            @if($setting->type == 1)
                            <div class="form-group">
                                <label>Valor (link URL)</label> <br>
                                <input type="text" name="value" class="form-control">
                            </div>
                            @endif
                            @if($setting->type == 2)
                            <div class="form-group">
                                <label>Valor </label> <br>
                                <input type="text" name="value" class="form-control">
                            </div>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                Actualizar
                            </button>
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
