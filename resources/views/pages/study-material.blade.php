@extends('layouts.frontpages')

@section('content')
@include('partials.header')

<div class="page study-material">
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
        <div class="row material-content">
            @if (count($documents))
            @for($i=0; $i < count($documents); $i++)
            <div class="col-md-3">
                <a href="{{url('storage/'.$documents[$i]->url)}}" target="_blank" rel="noopener noreferrer">
                    @if($i % 2 == 0)
                    <div class="material-item even">
                        @else
                        <div class="material-item odd">
                            @endif
                            <div class="image">
                                <img src="{{asset($documents[$i]->logo)}}" class="img-responsive">
                            </div>
                            <div class="title">{{str_limit($documents[$i]->name, 40)}}</div>
                            <div class="type">
                                Categoría: <span class="date">{{$documents[$i]->type}}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endfor
                @else
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p class="txt-center">No hay material de estudio en el momento.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @include('partials.footer')
    @stop
