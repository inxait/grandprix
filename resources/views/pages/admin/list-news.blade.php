@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard list-news">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Categorías <small><a href="{{url('dashboard/categorias/crear')}}">Crear categoría</a></small></h2>
                     <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Fecha de creación</th>
                                    <th>Fecha de actualización</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{$item->updated_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h2>Noticias <small><a href="{{url('dashboard/noticias/crear')}}">Crear noticia</a></small></h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titulo</th>
                                    <th>Imagen</th>
                                    <th>Resumen</th>
                                    <th>URL</th>
                                    <th>Publicada</th>
                                    <th>Fecha de creación</th>
                                    <th>Fecha de actualización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($news as $item)
                                <tr>
                                    <td>{{$item->title}}</td>
                                    <td>
                                        <img src="{{asset('storage/'.$item->image)}}" class="img-responsive" width="300">
                                    </td>
                                    <td>{{str_limit($item->excerpt, 100)}}</td>
                                    <td>{{$item->slug}}</td>
                                    <td>{{($item->published)? 'Si' : 'No'}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{$item->updated_at}}</td>
                                    <td>
                                        <a href="{{route('dashboard.edit-news', $item->id)}}">Editar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$news->links()}}
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>

    @include('partials.footer')
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
