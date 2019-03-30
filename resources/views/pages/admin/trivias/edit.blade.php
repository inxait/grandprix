@extends('layouts.adminpages')

@section('content')
  @include('partials.header')
    <div class="page dashboard edit-trivias">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    @include('partials.admin.side-menu')
                </div>
                <div class="col-md-8">
                    <h2>
                        Editar Trivia
                        <small><a href="{{url('dashboard/trivias')}}">Volver</a></small>
                    </h2>
                    <br>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{route('trivias.update', $trivia->id)}}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      {{  method_field('PUT') }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre *</label>
                                    <input type="text" class="form-control" name="name" value="{{ $trivia->name }}">
                                </div>
                                <div class="form-group">
                                    <label>Descripción *</label>
                                    <textarea cols="30" rows="3" class="form-control" name="description">{{ $trivia->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Fecha de inicio *</label>
                                    <input type="text" class="form-control start-date" placeholder="DD/MM/YYYY"
                                           name="start_date" value="{{ date_format(new DateTime($trivia->start_date), 'd/m/Y') }}">
                                </div>
                                <div class="form-group">
                                    <label>Fecha de finalización *</label>
                                    <input type="text" class="form-control finish-date" placeholder="DD/MM/YYYY"
                                           name="finish_date" value="{{ date_format(new DateTime($trivia->finish_date), 'd/m/Y') }}">
                                </div>
                                <div class="form-group">
                                    <label>Calificación total * <small>(Este valor se divide por el número de preguntas)</small></label>
                                    <input type="text" class="form-control" name="total_val" placeholder="ej: 100" value="{{ $trivia->total_val }}">
                                </div>
                                <div class="form-group">
                        					<label>Premio menor (ranking top 5)</label>
                        					<select name="reward_id" class="form-control">
                                    <option value="" selected disabled>Seleccione el premio</option>
                        						@foreach($rewards as $value)
                                      <option value="{{$value->id}}" {{($value->id == $trivia->reward_id) ? 'selected' : ''}}>{{$value->title}}</option>
                                    @endforeach
                        					</select>
                        				</div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Premio 90% a 100% correctas *</label>
                                    <input type="text" class="form-control" name="points_all_correct" value="{{ $trivia->points_all_correct }}">
                                </div>
                                <div class="form-group">
                                    <label>Premio 70% a 89% correctas</label>
                                    <input type="text" class="form-control" name="points_some_correct" value="{{ $trivia->points_some_correct }}">
                                </div>
                                <div class="form-group">
                                    <label>
                                        Material de estudio (PDF, DOC) &nbsp;
                                        <small>
                                            Cargue otro archivo si quiere reemplazar
                                            <a href="{{url('storage/'.$trivia->study_information)}}"
                                               target="_blank" rel="noopener noreferrer">el anterior</a>
                                        </small>
                                    </label>
                                    <input type="file" class="form-control" multiple="false" name="study_information">
                                </div>
                                <div class="form-group">
                                    <label>Numero de preguntas aleatorias</label>
                                    <input type="text" class="form-control" name="number_questions_random" value="{{ $trivia->number_questions_random }}">
                                </div>
                                <div class="form-group">
                                    <label>
                                        Excel de preguntas y respuestas *
                                        <small><a href="{{asset('formats/preguntas_test_y_trivias.xlsx')}}">Descargar formato ejemplo</a></small>
                                    </label>
                                    <input type="file" class="form-control" multiple="false" name="excel_questions">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Actualizar Trivia</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
@endsection
