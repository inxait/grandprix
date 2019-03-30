@extends('layouts.frontpages')

@section('content')
@include('partials.header')

<div class="page news">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if(Auth::user()->hasRole('seller'))
                @include('partials.seller-menu')
                @else
                @include('partials.dealer-menu')
                @endif
            </div>
        </div>
        <div class="row news-content">
            <div class="col-md-12">
                <div class="panel panel-default">
                  <div class="panel-body news-detail">
                    <h2>
                        {{$news->title}} <br>
                        @if(Auth::user()->hasRole('seller'))
                        <small><a href="{{route('news')}}">Volver</a></small>
                        @else
                        <small><a href="{{route('dealer-news')}}">Volver</a></small>
                        @endif
                    </h2>
                    <div class="image">
                        <img src="{{asset('storage/'.$news->image)}}" class="img-responsive">
                    </div>
                    <div class="update-info">
                        {{'Última actualización: '.$news->updated_at}}
                    </div>
                    <div class="description">
                        {!! $news->description !!}
                    </div>
                    @if(!is_null($news->related_material))
                    <div class="related">
                        <p><strong>Material Relacionado</strong></p>
                        @if($news->type_related_material == "document")
                        <a href="{{url('storage/'.$news->related_material)}}"
                         target="_blank" rel="noopener noreferrer">Ver Documento</a>
                         @endif
                         @if($news->type_related_material == "image")
                         <image src="{{url('storage/'.$news->related_material)}}" style="width:100%;height:400px;" class="img-responsive">
                            @endif
                            @if($news->type_related_material == "video")
                            <video width="100%" height="400" controls>
                                <source src="{{url('storage/'.$news->related_material)}}" type="video/mp4">
                                </video>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
    @stop
