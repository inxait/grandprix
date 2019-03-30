@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Ranking general</div>
                                <div class="panel-body">
                                    <div class="ranking-wrapper">
                                        <div class="ranking-table table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    @if (count($ranking))
                                                    @foreach($ranking as $user)
                                                    <tr>
                                                        <td>
                                                            @if(!is_null($user->avatar()->first()))
                                                            <img src="{{asset('images/avatar/helmet/'.$user->avatar()->first()->helmet.'.png')}}" class="img-responsive">
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Usuarios
                                    <a class="pull-right" href="{{route('dashborad.download-report')}}">Descargar Informe</a>
                                </div>
                                <div class="panel-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td><label>Usuarios sin activar cuenta</label></td>
                                                <td>{{$totalActivePendingUsers[0]->total}}</td>
                                            </tr>
                                            <tr>
                                                <td><label>Usuarios Registrados</label></td>
                                                <td>{{$totalRegisteredUsers[0]->total}}</td>
                                            </tr>
                                            <tr>
                                                <td><label>Usuarios Totales</label></td>
                                                <td>{{$totalUsers[0]->total}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop
