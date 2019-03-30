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
                        <div class="col-md-6">
                            <h2>Formato de carga de usuarios</h2>
                            <a href="{{asset('formats/formato_asesores.xlsx')}}">Descargar formato</a>
                        </div>
                        <div class="col-md-6">
                            <h2>Cargar excel de usuarios</h2>
                            <form method="POST" enctype="multipart/form-data" action="{{route('upload-users')}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>Excel de usuarios</label>
                                    <input type="file" name="users_excel" class="form-control">
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
                </div>
                <div class="col-md-1"></div>
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
