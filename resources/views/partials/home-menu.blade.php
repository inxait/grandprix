<ul class="nav nav-home nav-pills">
    <li role="presentation" @if (request()->route()->named('about')) class="active" @endif>
        <a href="{{url('que-es-grand-prix')}}">¿Qué es Grand prix?</a>
    </li>
    <li role="presentation" @if (request()->route()->named('how-it-works')) class="active" @endif>
        <a href="{{url('como-gano-kms')}}">¿Cómo gano kilómetros?</a>
    </li>
</ul>
