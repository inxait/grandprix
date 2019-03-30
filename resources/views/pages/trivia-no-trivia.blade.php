@extends('layouts.frontpages')

@section('content')
    @include('partials.header')

    <div class="page trivia-unavailable">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.seller-menu')
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="no-trivia-message">
                        En el momento <span class="bold">no hay trivia Grand Prix</span>
                        tienes que estar pendiente de las comunicaciones de esta
                        <span class="bold">gran carrera para que participes y acumules más KM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop
