@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard create-category">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Crear categoría <small><a href="{{url('dashboard/noticias')}}">Volver</a></small></h2>
                    <form method="POST" enctype="multipart/form-data" action="{{route('create-category')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="category_name" class="form-control" maxlength="100">
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


