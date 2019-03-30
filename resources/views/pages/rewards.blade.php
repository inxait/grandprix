@extends('layouts.frontpages')

@section('content')
@include('partials.header')

<div class="page rewards">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('partials.seller-menu')
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <br>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h2>Premio mensual</h2>
                        <div class="prize">
                            @if (isset($data['montly']))
                            @if (!is_null($data['montly']))
                            <img src="{{asset('storage/'.$data['montly']->image)}}"
                            class="img-responsive"><br>
                            <p>
                                <small>{{$data['montly']->description}}</small>
                            </p>
                            @endif
                            @else
                            <p>No hay premios mensuales</p>
                            @endif
                        </div>
                        <hr>
                        <h2>Premio trimestral</h2>
                        <div class="prize">
                            @if (isset($data['trimester']))
                            @if (!is_null($data['trimester']))
                            <img src="{{asset('storage/'.$data['trimester']->image)}}"
                            class="img-responsive"><br>
                            <p>
                                <small>{{$data['trimester']->description}}</small>
                            </p>
                            @endif
                            @else
                            <p>No hay premios trimestrales</p>
                            @endif
                        </div>
                        <hr>
                        @foreach($measures as $measure)
                        <h2>Gran premio {{$measure->name}}</h2>
                        <div class="prize">
                            @if (!is_null($data[studly_case($measure->name)]))
                            <img src="{{asset('storage/'.$data[studly_case($measure->name)]->image)}}"
                            class="img-responsive"><br>
                            <p>
                                <small>{{$data[studly_case($measure->name)]->description}}</small>
                            </p>
                            @else
                            <p>No hay premios {{$measure->name}}</p>
                            @endif
                        </div>
                        <hr>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <small class="legal-info">
                        * Los premios podrán variar a lo largo del año, dependiendo las estrategias comerciales definidas por la marca ACDelco. Cualquier variación podrá ser hecha sin previo aviso, y será publicada en el sitio web.
                        <br>
                    </small>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')
@stop
