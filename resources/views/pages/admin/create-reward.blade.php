@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard create-reward">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Crear premio <small><a href="{{url('dashboard/premios')}}">Volver</a></small></h2>
                    <form method="POST" enctype="multipart/form-data" action="{{route('create-reward')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Título</label>
                                    <input type="text" name="title" class="form-control" maxlength="80">
                                </div>
                                <div class="form-group">
                                    <label>Imagen</label>
                                    <input type="file" name="image"
                                           accept=".png, .jpg, .jpeg">
                                </div>
                                <div class="form-group">
                                    <label>Tipo de premio</label>
                                    <select name="type" class="form-control">
                                        <option value="ocasional">Ocasional</option>
                                        <option value="mensual">Mensual</option>
                                        <option value="trimestral">Trimestral</option>
                                        @foreach($measures as $measure)
                                        <option value="{{mb_strtolower($measure->name)}}">
                                            {{$measure->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Información legal</label>
                                    <input type="file" name="legal"
                                           accept=".pdf, .docx, .doc">
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
