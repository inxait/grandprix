@extends('layouts.adminpages')

@section('content')
@include('partials.header')

<div class="page dashboard list-documents">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-2">
                @include('partials.admin.side-menu')
            </div>
            <div class="col-md-8">
                <h2>Documentos <small><a href="{{route('document.create')}}">Crear</a></small></h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Archivo</th>
                                <th>Logo</th>
                                <th>Fecha de Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($documents))
                            @foreach($documents as $d)
                            <tr>
                                <td>{{$d->name}}</td>
                                <td>
                                    <a href="{{asset('storage/'.$d->url)}}" target="_blank" rel="noopener noreferrer">
                                        Ver archivo
                                    </a>
                                </td>
                                <td>
                                    <img src="{{asset($d->logo)}}" class="img-responsive">
                                </td>
                                <td>{{$d->created_at}}</td>
                                <td>
                                    <a href="#" data-link="{{route('document.delete', $d->id)}}"
                                        class="link-confirmation" data-toggle="modal"
                                        data-target="#confirmModal">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="alert alert-warning"><center>No existen archivos disponibles.</center></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    {{$documents->links()}}
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</div>

@include('partials.footer')
@include('partials.admin.confirmation-modal', [
    'description' => '¿Está seguro de eliminar el contenido? No se podrá revertir la acción.'
    ])
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
