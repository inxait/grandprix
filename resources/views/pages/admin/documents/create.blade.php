@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard create-document">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>
                      Crear Documento
                      <small><a href="{{url('dashboard/material')}}">Volver</a></small>
                    </h2>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{route('document.store')}}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="name" class="form-control" value="{{old('name')}}">
                                </div>
                                <div class="form-group">
                                    <label>Archivo</label>
                                    <input type="file" class="form-control" multiple="false" name="file" value="{{old('file')}}">
                                </div>
                                <div class="alert alert-warning msj-up-document" role="alert">Extensiones permitidas (jpg,jpeg,gif,png,xls,xlsx,doc,docx,pdf,mp4,webm,ogg)</div>
                                <div class="form-group">
                                    <label>Contenido para usuario:</label>
                                    <select name="role_id" class="form-control">
                                        <option value="0">Todos los roles</option>
                                        @foreach($roles as $item)
                                        <option value="{{$item->id}}">{{ucfirst($item->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-block">
                                    Crear
                                </button>
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

    @if (session('status'))
    humane.info = humane.spawn({
      addnCls: 'humane-flatty-success',
      timeout: 3500
    });

    humane.info("{{ session('status') }}");
    @endif
    </script>
@stop
