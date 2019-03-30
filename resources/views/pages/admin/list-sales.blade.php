@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard list-news">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Formato de carga de ventas</h2>
                            <a href="{{asset('formats/formato_ventas.xlsx')}}">Descargar formato</a>
                        </div>
                        <div class="col-md-6">
                            <h2>Cargar excel de ventas</h2>
                            <form method="POST" enctype="multipart/form-data" action="{{route('upload-sales')}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>Excel de cumplimiento por vendedor</label>
                                    <input type="file" name="sales_excel" class="form-control">
                                </div>
                                <div class="form-group hidden">
                                    <div class="alert alert-warning" role="alert">
                                        <strong>Procesando.</strong> Esta operación puede tardar varios minutos.
                                    </div>
                                </div>
                                <button id="btn-sales-excel" type="submit" class="btn btn-primary">Cargar</button>
                            </form>
                        </div>
                    </div>
                    <h2>
                        Control de carga
                        <small>Mostrando {{$totalSales[0]->total}} registros</small>
                    </h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mes</th>
                                    <th>Año</th>
                                    <th>Metrica</th>
                                    <th>Valor</th>
                                    <th>Distribuidor</th>
                                    <th>Vendedor</th>
                                    <th>Cédula</th>
                                    <th>Fecha de subida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $item)
                                <tr>
                                    <td>{{Calendar::getHumanMonth($item->month)}}</td>
                                    <td>{{$item->year}}</td>
                                    <td>{{$item->measure->name}}</td>
                                    <td>{{$item->value.' '.$item->measure->units}}</td>
                                    <td>{{$item->distributor}}</td>
                                    <td>{{$item->seller->first_name.' '.$item->seller->last_name}}</td>
                                    <td>{{$item->seller->identification}}</td>
                                    <td>{{$item->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$sales->links()}}
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>

    @include('partials.footer')
@stop

@section('scripts')
<script>
@if (session('status'))
    humane.info = humane.spawn({
      addnCls: 'humane-flatty-success',
      timeout: 3500
    });

    humane.info("{{ session('status') }}");
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        humane.error("{{$error}}");
    @endforeach
@endif
</script>
@stop
