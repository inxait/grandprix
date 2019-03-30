@extends('layouts.adminpages')

@section('content')
    @include('partials.header')

    <div class="page dashboard create-trivia">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>
                      Crear trivia
                      <small><a href="{{url('dashboard/trivias')}}">Volver</a></small>
                    </h2>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{route('trivias.store')}}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                  		<div class="row">
                  			<div class="col-md-6">
                  				<div class="form-group">
                  					<label>Nombre</label>
                  					<input type="text" name="name" class="form-control" value="{{old('name')}}">
                  				</div>
                  				<div class="form-group">
                  					<label>Descripción</label>
                  					<textarea cols="30" rows="3" class="form-control" name="description" value="{{old('description')}}"></textarea>
                  				</div>
                  				<div class="form-group">
                  					<label>Fecha de inicio</label>
                  					<input type="text" class="form-control start-date" placeholder="DD/MM/YYYY" name="start_date" value="{{old('start_date')}}">
                  				</div>
                  				<div class="form-group">
                  					<label>Fecha de finalización</label>
                  					<input type="text" class="form-control finish-date" placeholder="DD/MM/YYYY" name="finish_date" value="{{old('finish_date')}}">
                  				</div>
                  				<div id="trivia-total-input" class="form-group">
                            <label>Calificación total * <small>(Este valor se divide por el número de preguntas)</small></label>
                            <input type="text" class="form-control" name="total_val" placeholder="ej: 100" value="{{old('total_val')}}">
                  				</div>
                  			</div>
                  			<div class="col-md-6">
                  				<div class="form-group">
                  					<label>Premio 90% a 100% correctas</label>
                  					<div class="input-group">
                  						<input type="text" class="form-control" name="points_all_correct" value="{{old('points_all_correct')}}">
                  						<div class="input-group-addon">KM</div>
                  					</div>
                  				</div>
                  				<div class="form-group">
                  					<label>Premio 70% a 89% correctas</label>
                  					<div class="input-group">
                  						<input type="text" class="form-control" name="points_some_correct" value="{{old('points_some_correct')}}">
                  						<div class="input-group-addon">KM</div>
                  					</div>
                  				</div>
                  				<div class="form-group">
                  					<label>Material de estudio (PDF, DOC)</label>
                  					<input type="file" class="form-control" multiple="false" name="study_information" value="{{old('study_information')}}">
                  				</div>
                  				<div class="form-group">
                  					<label>Premio menor (ranking top 5)</label>
                            <select name="reward_id" class="form-control">
                              <option value="" selected disabled>Seleccione el premio</option>
                              @foreach($rewards as $value)
                                <option value="{{$value->id}}">{{$value->title}}</option>
                              @endforeach
                            </select>
                  				</div>
                  				<div id="trivia-total-input" class="form-group">
                  					<label>Numero de preguntas aleatorias</label>
                  					<input type="number" name="number_questions_random" class="form-control" value="{{old('number_questions_random')}}">
                  				</div>
                  				<div class="form-group">
                  					<label>Excel preguntas y respuestas</label>
                  					<input id="excel-file" type="file" name="excel_questions" class="form-control">
                  					<a class="pull-right" href="/formats/preguntas_test_y_trivias.xlsx">Descargar formato </a>
                  				</div>
                  			</div>
                  		</div>

                  		<br>
                  		<div class="row">
                  			<div class="col-md-12">
                  				<button class="btn btn-primary btn-block" @click="createTrivia">
                  					Crear trivia
                  				</button>
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
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            humane.error("{{$error}}");
        @endforeach
    @endif

    @if (session('status'))
    humane.info = humane.spawn({
      addnCls: 'humane-flatty-success',
      timeout: 3500
    });

    humane.info("{{ session('status') }}");
    @endif
    </script>
@stop
