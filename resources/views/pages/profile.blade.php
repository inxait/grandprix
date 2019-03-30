@extends('layouts.frontpages')

@section('content')
    @include('partials.header')

    <div class="page profile">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.seller-menu')
                </div>
            </div>
            <div class="row profile-content">
                <div class="col-md-5">
                    <div class="character">
                        <div class="helmet">
                            <img src="{{ asset('images/avatar/helmet/'.Auth::user()->avatar()->first()->helmet.'.png')}}">
                        </div>
                        <div class="uniform">
                            <img src="{{asset('images/avatar/uniform/'.Auth::user()->avatar()->first()->uniform.'.png')}}">
                        </div>
                        <div class="gloves">
                            <img src="{{asset('images/avatar/gloves/'.Auth::user()->avatar()->first()->gloves.'.png')}}">
                        </div>
                        <div class="shoes">
                            <img src="{{asset('images/avatar/shoes/'.Auth::user()->avatar()->first()->shoes.'.png')}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-6">
                    <div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#timeline-km" aria-controls="timeline-km" role="tab" data-toggle="tab">
                                    Historial de KM
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#timeline-settlement" aria-controls="timeline-settlement" role="tab" data-toggle="tab">
                                    Historial de liquidaciones
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="timeline-km">
                                @foreach($points as $item)
                                <div class="alert" role="alert">
                                    <div class="name">{{$item->points_event}}</div>
                                    <div class="reward">{{$item->value.' km'}}</div>
                                </div>
                                @endforeach
                            </div>
                            <div role="tabpanel" class="tab-pane" id="timeline-settlement">
                                @if (count($liquidations))
                                @foreach($liquidations as $item)
                                <div class="alert" role="alert">
                                    <div class="name">{{'Liquidación '.json_decode($item->history)->reference}} <strong>{{$item->measure}}</strong></div>
                                    <div class="reward">
                                        Porcentaje de cumplimiento: {{round(json_decode($item->history)->current_percent, 2)}} %<br>
                                        Valor de entrega: {{'$'.number_format(json_decode($item->history)->receive_total)}}
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="alert" role="alert">
                                    <p>En el momento no tienes liquidaciones de ventas</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop
