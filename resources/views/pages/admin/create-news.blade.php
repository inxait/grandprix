@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard create-news">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Crear noticia <small><a href="{{url('dashboard/noticias')}}">Volver</a></small></h2>
                    <form id="create-news-form" method="POST" enctype="multipart/form-data" action="{{route('create-news')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Título</label>
                                    <input type="text" name="title" value="{{old('title', '')}}" class="form-control" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label>Resumen</label>
                                    <textarea name="excerpt" cols="30" rows="4"
                                        class="form-control" value="{{old('excerpt', '')}}" maxlength="299" placeholder="Resumen de la noticia max 300 caracteres"></textarea>
                                </div>
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Categorías</label>
                                        <div class="table-responsive">
                                        <select multiple="multiple" class="multiselect" name="categories[]">
                                            @foreach($categories as $item)
                                            <option value="{{$item->id}}">{{ucfirst($item->name)}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Imagen</label>
                                        <input type="file" name="image"
                                               accept=".png, .jpg, .jpeg">
                                    </div>
                                    <div class="form-group">
                                        <label>Material Relacionado (Opcional)</label>
                                        <input type="file" name="related_material">
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea class="wysiwyg form-control" value="{{old('description', '')}}" name="description"></textarea>
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

CKEDITOR.replace('description');
</script>
@stop
