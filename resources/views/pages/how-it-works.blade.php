@extends('layouts.frontpages')

@section('content')
    @include('partials.header')

    <div class="page how-it-works">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.seller-menu')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('partials.home-menu')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="how-item">
                        <div class="title">Activación</div>
                        <div class="description">
                            <div class="icon activation"></div>
                            <div class="name">Ganas 10 KM</div>
                            <div class="action">Por activarte</div>
                            <div class="conditions">
                                <p>
                                    Al estar activo en grand prix ya cuentas con 10 kms de entrada.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="how-item">
                        <div class="title">Trivias</div>
                        <div class="description">
                            <div class="icon trivia"></div>
                            <div class="name">Ganas KM</div>
                            <div class="action">Por participar y responder acertadamente una trivia</div>
                            <div class="conditions">
                                <p>
                                    Los asesores participarán en la trivia Grand Prix, donde los educaremos
                                    frente a la marca ACDelco.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="how-item">
                        <div class="title">Cumplimiento *</div>
                        <div class="description">
                            <div class="icon accomplish"></div>
                            <div class="name">Ganas 200 KM</div>
                            <div class="action">
                                Por cumplir las metas de cada línea de producto según
                                corresponda (lubricantes, baterías, autopartes)
                            </div>
                        </div>
                    </div>

                     <div class="how-item">
                        <div class="title">Sobrecumplimiento *</div>
                        <div class="description">
                            <div class="icon overaccomplish"></div>
                            <div class="name">Ganas 50 KM</div>
                            <div class="action">
                                Por cada venta adicional despues de haber cumplido la meta. Lubricantes: 6 galones, Baterías: 1 unidad, Autopartes: $250.000 COP
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <br>
                <small>
                    * La cantidad de kilómetros podrán variar a lo largo del año, dependiendo las estrategias
                    comerciales definidas por la marca ACDelco. Cualquier variación podrá ser hecha sin previo
                    aviso, y será publicada en el sitio web.
                    <br>
                </small>
                <br>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop
