<ul class="nav nav-admin nav-pills">
    <li role="presentation" @if (request()->route()->named('home')) class="active" @endif>
        <a href="{{url('dealer/inicio')}}">Home</a>
    </li>
    <li role="presentation" @if (request()->route()->named('news')) class="active" @endif>
        <a href="{{url('dealer/noticias')}}">Noticias</a>
    </li>
    <li role="presentation" @if (request()->route()->named('material')) class="active" @endif>
        <a href="{{url('dealer/material')}}">Material de Estudio</a>
    </li>
    <li>
        <a href="{{url('dealer/report')}}" title="Etapa Actual">Descargar Informe</a>
    </li>
</ul>
