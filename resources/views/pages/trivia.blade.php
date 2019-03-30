@extends('layouts.frontpages')

@section('content')
    @include('partials.header')

    <div class="page trivia">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.seller-menu')
                </div>
            </div>

            <div class="row trivia-main">
                <div class="col-md-6">
                    <div class="trivia-description">
                        <p>
                            {{$trivia->description}}
                        </p><br>
                        <a href="{{asset('storage/'.$trivia->study_information)}}" target="_blank">
                            Descarga el material aquí
                        </a>
                    </div>
                    @if($usrMadeTrivia)
                    <br>
                    <div class="form-group">
                        <div class="ranking-wrapper">
                            <p>Ya has participado de esta trivia</p>
                            <div class="ranking-table table-responsive">
                                <div class="title">RANKING TRIVIA {{$trivia->name}}</div>
                                <table class="table">
                                    <tbody>
                                        @foreach($currentRanking as $item)
                                        <tr>
                                            <td>
                                                <img src="{{asset('images/avatar/helmet/'.$item->user->avatar()->first()->helmet.'.png')}}" class="img-responsive">
                                            </td>
                                            <td>{{$loop->iteration}}.</td>
                                            <td class="name">
                                                <p class="surname">{{strtoupper($item->user->surname)}}</p>
                                                <p class="username">{{$item->user->first_name.' '.$item->user->last_name}}</p>
                                            </td>
                                            <td>
                                                <p>{{($item->correctness_percent > 0) ? round($item->correctness_percent, 1).'% correcta' : '0 % correcta' }}</p>
                                                <p>Tiempo de respuesta {{$item->time_to_respond}}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="trivia-button">
                        <button id="btn-trivia" class="btn btn-primary btn-block">
                            ¿Estás listo para participar? <br>
                            ¡Comienza la trivia ahora!
                        </button>
                    </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="reward-description">
                        <p>
                            <span class="big">Gana, aumenta tus KMS</span> y adicional te llevas
                            este gran beneficio.
                        </p>
                    </div>
                    <div class="reward-image">
                        <img src="{{asset('storage/'.$trivia->reward->image)}}" class="img-responsive">
                    </div>
                    <p class="reward-condition">Contesta y los 5 mejores tiempos se lo llevan</p>
                </div>
            </div>

            <div class="row trivia-game hidden">
                <div class="col-md-12">
                    <div id="start-trivia">
                        <trivia-test></trivia-test>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop
