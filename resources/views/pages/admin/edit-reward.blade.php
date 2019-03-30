@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard edit-reward">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Editar premio <small><a href="{{url('dashboard/premios')}}">Volver</a></small></h2>
                    <form method="POST" enctype="multipart/form-data" action="{{route('update-reward', $reward->id)}}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Título</label>
                                    <input type="text" name="title" class="form-control" maxlength="80" value="{{$reward->title}}">
                                </div>
                                <div class="form-group">
                                    <label>
                                        Imagen &nbsp;
                                        <small>
                                            Cargue otra imagen si quiere reemplazar
                                            <a href="{{url('storage/'.$reward->image)}}"
                                               target="_blank" rel="noopener noreferrer">la anterior</a>
                                        </small>
                                    </label>
                                    <input type="file" name="image"
                                           accept=".png, .jpg, .jpeg">
                                </div>
                                <div class="form-group">
                                    <label>Tipo de premio</label>
                                    <select name="type" class="form-control">
                                        <option value="ocasional" @if($reward->type == 'ocasional') selected @endif>Ocasional</option>
                                        <option value="mensual" @if($reward->type == 'mensual') selected @endif>Mensual</option>
                                        <option value="trimestral" @if($reward->type == 'trimestral') selected @endif>Trimestral</option>
                                        @foreach($measures as $measure)
                                        <option value="{{mb_strtolower($measure->name)}}" @if($reward->type == mb_strtolower($measure->name)) selected @endif>
                                            {{$measure->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="description" class="form-control">{{$reward->description}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Información legal &nbsp;
                                        <small>
                                            @if(!is_null($reward->legal))
                                            Cargue otro archivo si quiere reemplazar
                                            <a href="{{url('storage/'.$reward->legal)}}"
                                               target="_blank" rel="noopener noreferrer">el anterior</a>
                                            @endif
                                        </small>
                                    </label>
                                    <input type="file" name="legal"
                                           accept=".pdf, .docx, .doc">
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
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
