@extends('layouts.frontpages')

@section('content')
    @include('partials.header')

    <div class="page about">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.seller-menu')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('partials.home-menu')
                </div>
            </div>
            <div class="row">
                <div class="col-md-7"></div>
                <div class="col-md-5">
                    <div class="user">
                        <img src="{{asset('/images/ic-character.png')}}" class="img-responsive" width="100">
                        {{Auth::user()->first_name.' '.Auth::user()->last_name}}
                    </div>
                    <div class="program-description">
                        <span class="bold">Grand Prix ACDelco 2018 </span>es el programa
                        donde los asesores de venta de ACDelco, podrán recibir recompensas
                        por el cumplimiento de sus metas mensuales de venta, y además
                        acumular km que les permitirán acceder a premios durante el año.
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop
