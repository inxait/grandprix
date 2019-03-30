@extends('layouts.frontpages')

@section('content')
@include('partials.header')

<div class="page dealer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('partials.dealer-menu')
            </div>
        </div><br>
        <div class="row points-user">
            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="panel panel-stats">
                       <div class="panel-heading">{{$user->first_name}} {{$user->last_name}}</div>
                       <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                               <tbody>
                                 <tr>
                                    <td>Identificación:</td>
                                    <th>{{$user->identification}}</th>
                                </tr>
                                <tr>
                                    <td>Correo:</td>
                                    <th>{{$user->email}}</th>
                                </tr>
                                <tr>
                                    <td>Celular:</td>
                                    <th>{{$user->cellphone}}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-stats">
                <div class="panel-heading">{{$user->getPoints($user->id)}} Puntos</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Evento</th>
                                    <th>Puntaje</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($points))
                                @foreach ($points as $point)
                                <tr>
                                    <td>{{$point->points_event}}</td>
                                    <td>{{$point->value}}</td>
                                    <td>{{$point->created_at}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3" class="txt-center">En el momento no hay registros a mostrar.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

@include('partials.footer')
@stop
