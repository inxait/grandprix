<template>
	<form id="create-trivia-form" method="POST" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Nombre</label>
					<input type="text" name="trivia_name" class="form-control" maxlength="80">
				</div>
				<div class="form-group">
					<label>Descripción</label>
					<textarea name="trivia_description" class="form-control" rows="4" maxlength="300"></textarea>
				</div>
				<div class="form-group">
					<label>Fecha de inicio</label>
					<input type="text" name="start_date" class="start-date form-control" maxlength="10">
				</div>
				<div class="form-group">
					<label>Fecha de finalización</label>
					<input type="text" name="finish_date" class="finish-date form-control" maxlength="10">
				</div>
				<!--<div class="form-group">
					<label>Asignar puntos por respuesta</label>
					<div class="radio">
						<label>
							<input type="radio" class="radio-points" name="points_per_answer" value="si" checked>
							Si (cada pregunta tiene un valor de puntos configurable)
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" class="radio-points" name="points_per_answer" value="no">
							No (La trivia tiene un puntaje total, cada pregunta tiene un mismo valor)
						</label>
					</div>
				</div>-->
				<div id="trivia-total-input" class="form-group">
					<label>Puntaje total de la trivia</label>
					<input type="text" name="total_val" class="form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Premio 90% a 100% correctas</label>
					<div class="input-group">
						<input type="text" name="points_all_correct" class="form-control" maxlength="10">
						<div class="input-group-addon">KM</div>
					</div>
				</div>
				<div class="form-group">
					<label>Premio 70% a 89% correctas</label>
					<div class="input-group">
						<input type="text" name="points_some_correct" class="form-control" maxlength="10">
						<div class="input-group-addon">KM</div>
					</div>
				</div>
				<div class="form-group">
					<label>Material de estudio (PDF, DOC)</label>
					<input id="study-file" type="file" name="study_material" class="form-control">
				</div>
				<div class="form-group">
					<label>Premio menor (ranking top 5)</label>
					<select name="reward_id" class="form-control">
						<option v-for="reward in rewards" v-bind:value="reward.id">
							{{reward.title}}
						</option>
					</select>
				</div>
				<div id="trivia-total-input" class="form-group">
					<label>Numero de preguntas aleatorias</label>
					<input type="number" name="number_questions_random" class="form-control">
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
</template>

<script>
	import axios from 'axios';
	import addDays from 'date-fns/add_days'
    import { getJsonFormData, isDataEmpty} from '../utils';
    import QuestionComponent from './Question.vue';

    export default {
    	data() {
    		return {
    			rewards: [],
    			questionsData: [
    				{
    					index: 1,
    					title: '',
    					time: 30,
    					correct_answer: null,
    					answers: []
    				}
    			]
    		};
    	},
    	beforeCreate() {
    		const self = this;
    		axios.get('/rewards').then((res) => {
    			if (res.data.success) {
    				self.rewards = res.data.info;
    			}
    		});
    	},
    	created() {
    		this.$on('title-changed', (obj) => {
		        this.questionsData[obj.index - 1].title = obj.title;
		    });

		    this.$on('time-changed', (obj) => {
		        this.questionsData[obj.index - 1].time = obj.time;
		    });

		    this.$on('correct-changed', (obj) => {
		        this.questionsData[obj.index - 1].correct_answer = obj.correctAnswer;
		    });

		    this.$on('answers-changed', (obj) => {
		        this.questionsData[obj.index - 1].answers = obj.answers;
		    });
    	},
    	mounted() {
    		const today = new Date();
    		const tomorrow = addDays(today, 1);

    		$('.start-date').datepicker({
			  autoclose: true,
			  format: 'dd/mm/yyyy',
			  startDate: 'today',
			  language: 'es'
			});

			$('.finish-date').datepicker({
			  autoclose: true,
			  format: 'dd/mm/yyyy',
			  startDate: tomorrow,
			  language: 'es'
			});
    	},
    	methods: {
    		createTrivia(e) {
    			e.preventDefault();
    			const data = getJsonFormData('#create-trivia-form');

    			if (isDataEmpty(data)) {
    				humane.error('Hay campos vacios');
    			} else {
    				let hasIncompleteQuestions = false;
    				this.questionsData.forEach((question) => {
    					if (!question.title || question.correct_answer == null || question.answers.length < 4) {
    						hasIncompleteQuestions = true;
    					}
    				});

  					const formData = new FormData();
  					const studyFile = document.querySelector('#study-file');
						const excelFile = document.querySelector('#excel-file');
  					formData.append("study_material", studyFile.files[0]);
						formData.append("excel_questions", excelFile.files[0]);

  					data.questions = JSON.stringify(this.questionsData);

  					const keys = Object.keys(data);

  					keys.forEach((item) => {
  						formData.append(item, data[item]);
  					});

  					axios.post('/trivias/create', formData, {
  						headers: {
					      'Content-Type': 'multipart/form-data'
					    }
  					})
  					.then((res) => {
				      if (res.data.success) {
				        window.location.href = res.data.info;
				      } else {
				      	res.data.errors.forEach((item) => {
				            humane.error(item);
				        });
				      }
				    });
    			}
    		}
    	},
    	components: {
	      'question-component': QuestionComponent,
	    }
    }
</script>
