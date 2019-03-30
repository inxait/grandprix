@extends('layouts.frontpages')

@section('content')
@include('partials.header')

<div class="page dealer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('partials.dealer-menu')
            </div>
            <div class="col-md-12">
              @if (session('status'))
                  <div class="alert alert-success">
                      {{ session('status') }}
                  </div>
              @endif
            </div>
        </div>
        <br>
        <div class="row">
            <div class="row list-users">
                <h2>Usuarios {{$distributor->name}}</h2>
                <a href="{{url('dealer/download')}}">Descargar</a>
                <form class="navbar-form navbar-left pull-right" role="search" method="GET" action="{{url('dealer/inicio')}}">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Nombres, Documento" name="search">
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Identificación</th>
                            <th>Correo</th>
                            <th>Puntaje</th>
                            <th>Ver más</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($users))
                        @foreach($users as $user)
                        <tr>
                            <td>{{$user->first_name}} {{$user->last_name}}</td>
                            <td>{{$user->identification}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->getPoints($user->user_id)}}</td>
                            <td><a class="btn btn-sm btn-default" href="{{url('dealer/points-user')}}/{{$user->user_id}}">Ir</a> </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="alert alert-warning"><center>No hay registros.</center></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                {{$users->links()}}
            </div>
        </div>
    </div>
</div>

@include('partials.footer')
@stop
