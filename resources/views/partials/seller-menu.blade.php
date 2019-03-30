<ul class="nav nav-admin nav-pills">
    <li role="presentation" @if (request()->route()->named('home')) class="active" @endif data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <a href="#">Home</a>
    </li>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li>
            <a href="{{url('inicio')}}">
                Ranking
            </a>
        </li>
        <li>
            <a href="{{url('que-es-grand-prix')}}">
                ¿Qué es Grand Prix?
            </a>
        </li>
        <li @if (request()->route()->named('how-it-works')) class="active" @endif>
            <a href="{{url('como-gano-kms')}}">¿Cómo gano KMS?</a>
        </li>
        <li @if (request()->route()->named('material')) class="active" @endif>
            <a href="{{url('material')}}">Material de Estudio</a>
        </li>
    </ul>
    <li role="presentation" @if (request()->route()->named('trivia-home')) class="active" @endif>
        <a href="{{url('trivias-grand-prix')}}">Trivia Grand Prix</a>
    </li>
    <li role="presentation" @if (request()->route()->named('rewards')) class="active" @endif>
        <a href="{{url('premios-grand-prix')}}">Premios Grand Prix</a>
    </li>
    <li role="presentation" @if (request()->route()->named('news')) class="active" @endif>
        <a href="{{url('noticias')}}">Noticias</a>
    </li>
    <li role="presentation" @if (request()->route()->named('profile')) class="active" @endif>
        <a href="{{url('perfil')}}">Perfil</a>
    </li>
</ul>
