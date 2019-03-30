@php
    $currentPeriod = Calendar::getCurrentPeriod();
@endphp

@if (Auth::check())
<header class="main-header auth">
@else
<header class="main-header">
@endif
    <div class="container-fluid">
        <div class="row">
            @if (Auth::check())
            <div class="col-md-6">
                <div class="user">
                    <div class="avatar">
                        <img src="{{asset('images/ic-character.png')}}" class="img-responsive">
                    </div>
                    <div class="name">
                    {{Auth::user()->first_name}} <br>
                    @if (Auth::user()->hasRole('seller'))
                    <a href="{{url('usuarios/mi-perfil')}}">
                        <small>Editar mi perfil</small>
                    </a>
                    @else
                    <a href="{{url('admin/mi-perfil')}}">
                        <small>Editar mi perfil</small>
                    </a>
                    @endif
                    </div>
                </div>
                @if (Auth::user()->hasRole('seller'))
                    <div class="points">
                        {{Points::getUserTrimesterPoints(Auth::user()->id, $currentPeriod) .' km'}} <br>
                        <small>Este trimestre</small>
                    </div>
                @endif
                <div class="logout">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Cerrar sesión &#8227;
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="logo-grandprix">
                    <a href="{{url('/')}}">
                    <img src="{{asset('images/logo-grandprix.png')}}" alt="Grandprix" class="img-responsive">
                    </a>
                </div>
                 <div class="logo-acdelco">
                    <img src="{{asset('images/logo-acdelco.png')}}" alt="Acdelco" class="img-responsive">
                </div>
            </div>
            @else
            <div class="col-md-6">
                <div class="logo-grandprix">
                    <a href="{{url('/')}}">
                    <img src="{{asset('images/logo-grandprix.png')}}" alt="Grandprix" class="img-responsive">
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="logo-acdelco">
                    <img src="{{asset('images/logo-acdelco.png')}}" alt="Acdelco" class="img-responsive">
                </div>
            </div>
            @endif
        </div>
    </div>
</header>
