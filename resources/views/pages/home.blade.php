@extends('layouts.frontpages')

@section('content')
@include('partials.header')

<div class="page home">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('partials.seller-menu')
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12">
                <div class="table table-responsive">
                    <table class="table table-striped goal">
                        <tr class="goal-month">
                            <td colspan="2">TUS METAS DEL MES DE {{$month}}</td>
                        </tr>
                        @if(count($goals))
                        @foreach($goals as $g)
                        <tr class="goal-info">
                            <td>{{$g['measure']}}</td>
                            <td>{{($g['measure'] == 'Autopartes') ? '$ '. number_format($g['goal']) : number_format($g['goal'])}}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr class="goal-info sinreg">
                            <td colspan="2">No hay registros a mostrar.</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            <div class="row home-info">
                <div class="col-md-5">
                    <div class="ranking-wrapper">
                        <div id="main-ranking" class="ranking-table table-responsive">
                            <div class="title">RANKING TRIMESTRE {{$currentPeriod}} GRAND PRIX</div>
                            <table class="table">
                                <tbody>
                                    @if(count($ranking))
                                    @foreach($ranking as $user)
                                    <tr>
                                        <td>
                                            @if(!is_null($user->avatar))
                                            <img src="{{asset('images/avatar/helmet/'.$user->avatar.'.png')}}" class="img-responsive">
                                            @else
                                            <img src="" class="img-responsive">
                                            @endif
                                        </td>
                                        <td>{{$loop->iteration}}.</td>
                                        <td class="name">
                                            <p class="surname">{{strtoupper($user->surname)}}</p>
                                            <p class="username">{{$user->first_name.' '.$user->last_name}}</p>
                                        </td>
                                        <td class="km">{{$user->total_points}} KM</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td>Aún no hay datos de vendedores disponibles.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="track-map">
                        <button class="btn-period period-1" data-period="1">1</button>
                        @if ($currentPeriod > 1)
                        <button class="btn-period period-2" data-period="2">2</button>
                        @endif
                        @if ($currentPeriod > 2)
                        <button class="btn-period period-3" data-period="3">3</button>
                        @endif
                        @if ($currentPeriod > 3)
                        <button class="btn-period period-4" data-period="4">4</button>
                        @endif

                        <img src="{{asset('images/bg-track.png')}}"
                        alt="Grand prix track" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
    @stop
