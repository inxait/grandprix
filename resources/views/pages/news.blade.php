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
            @if (count($news))
            <div class="col-md-12">
                @for($i=0; $i < count($news); $i++)
                @if(Auth::user()->hasRole('seller'))
                <a href="{{route('news-detail', $news[$i]->slug)}}">
                @else
                <a href="{{route('dealer-news-detail', $news[$i]->slug)}}">
                @endif
                    @if($i % 2 == 0)
                    <div class="news-item even">
                        @else
                        <div class="news-item odd">
                            @endif
                            <div class="image">
                                <img src="{{asset('storage/'.$news[$i]->image)}}" class="img-responsive">
                            </div>
                            <div class="excerpt">
                                {{$news[$i]->excerpt}}
                            </div>
                            <div class="title">{{str_limit($news[$i]->title, 40)}}</div>
                        </div>
                    </a>
                    @endfor
                </div>
                @else
                <div class="col-md-12">
                    <div class="panel panel-default">
                      <div class="panel-body">
                        <p class="txt-center">No hay noticias publicadas en el momento.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('partials.footer')
@stop
