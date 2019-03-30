@php
$menu = [
    ['name' => 'Usuarios', 'url' => 'dashboard/usuarios'],
    ['name' => 'Metricas y cumplimiento', 'url' => 'dashboard/metricas'],
    ['name' => 'Cargar ventas', 'url' => 'dashboard/cargar-ventas'],
    ['name' => 'Liquidaciones', 'url' => 'dashboard/liquidaciones'],
    ['name' => 'Premios', 'url' => 'dashboard/premios'],
    ['name' => 'Trivias', 'url' => 'dashboard/trivias'],
    ['name' => 'Noticias', 'url' => 'dashboard/noticias'],
    ['name' => 'Material de Estudio', 'url' => 'dashboard/material'],
    ['name' => 'Configuraciones', 'url' => 'dashboard/configuraciones'],
    ['name' => 'Puntos', 'url' => 'dashboard/puntos'],
    ['name' => 'Informes', 'url' => 'dashboard/informes'],
    ['name' => 'Backups', 'url' => 'dashboard/backups'],
];
@endphp

<ul class="nav nav-pills nav-stacked">
    @foreach($menu as $item)
    <li @if(strrpos(url()->current(), $item['url']) != false) class="active" @endif>
         <a href="{{url($item['url'])}}">{{$item['name']}}</a>
        @if ($item['name'] == "Usuarios")
        <ul>
              <li class="nav-item"><a href="{{url('dashboard/pendientes')}}">Pendientes por Aprobación</a></li>
              <li class="nav-item"><a href="{{url('dashboard/inactivos')}}">Sin Activar Cuenta</a></li>
              <li class="nav-item"><a href="{{url('dashboard/registrados')}}">Registrados</a></li>
         </ul>
         @endif
    </li>
    @endforeach
</ul>
