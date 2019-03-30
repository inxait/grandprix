@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard list-reports">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>Reportes de ventas</h2>
                    <form method="POST" action="{{route('create-report')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <select name="month" class="form-control">
                                    @foreach($months as $month)
                                    <option value="{{$month}}">{{ucfirst($month)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button id="create-report" class="btn btn-primary">Crear</button>
                            </div>
                            <div class="col-md-12">
                                <div class="progress hidden txt-center">
                                  <img src="{{asset('images/loader.gif')}}" alt="Cargando..." class="img-responsive">
                                </div>
                            </div>
                        </div>
                    </form>
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
