<template>
	<div :id="`question${question.index}`" class="question">
		<div class="col-md-12">
			<h2>Pregunta #{{question.index}}</h2>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<label>Titulo pregunta</label>
				<input type="text" class="form-control" v-model="question.title">
			</div>
			<div class="form-group">
				<label>Tiempo para responder</label>
				<div class="input-group">
					<input type="text" class="form-control" v-model="question.time">
					<div class="input-group-addon">segundos</div>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<h2>Respuestas pregunta</h2>
		</div>
		<div v-for="n in 4" class="answer form-group">
			<div class="col-md-12">
				<h3>Respuesta {{n}}</h3>
			</div>
			<div class="col-md-6">
				<label>Descripción</label>
				<input type="text" v-model="question.answers[n-1]" class="form-control">
			</div>
			<div class="col-md-6">
				<label>Respuesta correcta</label>
				<div class="radio">
					<label>
						<input type="radio"
							   :class="`right-answer${question.index}`"
							   :name="`question${question.index}-answer${n}`" @click="updateCorrect(n, $event)">
						Si
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio"
							   :class="`wrong-answer${question.index}`"
							   :name="`question${question.index}-answer${n}`">
						No
					</label>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-md-offset-6">
			<a class="delete-link" @click="removeQuestion">
               	Eliminar Pregunta
            </a>
		</div>
	</div>
</template>

<script>
	export default{
		data() {
    		return {};
    	},
		props: {
	      question: {
	        type: Object,
	        required: true,
	      },
	    },
	    methods: {
	    	updateCorrect(n, e) {
	    		const idx = this.question.index;
	    		$(`#question${idx} .right-answer${idx}`).prop('checked', false);
	    		$(e.target).prop('checked', true);
	    		this.question.correct_answer = n;
	    	},
	    	removeQuestion(e) {
	    		e.preventDefault();
        		this.$parent.removeQuestion(this.question.index);
	    	}
	    },
	}
</script>
