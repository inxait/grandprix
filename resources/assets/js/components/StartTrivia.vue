<template>
	<div>
		<h1>Bienvenido a la trivia: {{trivia.name}}</h1>
        <div v-for="(question, idx) in trivia.questions" v-if="idx == (currentQuestion)" class="question">
            <div class="row">
                <div class="timer">
                    <countdown  v-on:countdownend="onCountdownEnd(idx, idx + 1)"
                                v-on:countdownstart="onCountdownStart(idx)"
                                v-on:countdownprogress="onCountdownProgress"
                                ref="countdown" :time="question.seconds_to_answer">
                        <template slot-scope="props">
                            Tiempo para responder：<span class="seconds-left">{{ props.minutes }} : {{props.seconds }}</span>
                        </template>
                    </countdown>
                </div>
            </div>
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h2>{{question.title}}</h2>
                        </div>
                        <div v-for="answer in question.answers" class="col-md-6 answer">
                            <div class="radio">
                                <label>
                                    <input type="radio" :name="`question${question.id}`"
                                          :value="answer.id" v-model="answers[idx].answer">
                                    {{answer.description}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-6">
                    <button :id="`next${idx}`" disabled="true" class="btn btn-action" @click="showNextQuestion(idx, idx + 1)">
                        <span>Siguiente</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="results" v-if="triviaFinished">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h2 class="txt-center">
                        Ha terminado la trivia. Con esta trivia has ganado {{achievedPoints}} KM. <br>
                        Estos son los resultados:
                    </h2>
                    <br>
                    <div v-for="(result, idx) in results" class="col-md-6 answer">
                        <div class="form-group">
                            <label>Pregunta {{idx + 1}} </label>
                            <p>{{result.question}}</p>
                            <p>Puntos: {{(result.answer != 0) ? Number(result.answer).toPrecision(3) : 0}}</p>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-6 col-md-offset-6">
                        <button class="btn btn-primary" @click="finishTrivia" :disabled="disabled">Continuar</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
	import axios from 'axios'
    import VueCountdown from '@xkeshi/vue-countdown'

	export default {
    	data() {
    		return {
                answers: [],
                results: [],
                achievedPoints: 0,
                triviaFinished: false,
    			currentQuestion: 0,
    			trivia: {},
                disabled: false
    		}
    	},
    	mounted() {
            const self = this

            axios.get('active-trivia').then((res) => {
                if (res.data.success) {
                    self.trivia = res.data.info

                    res.data.info.questions.forEach((item) => {
                        let obj = {}
                        obj.question = item.id
                        obj.answer = null
                        obj.time_left = 0

                        self.answers.push(obj)
                    })
                }
            })
    	},
        methods: {
            onCountdownStart(idx) {
                setTimeout(function() {
                    document.getElementById(`next${idx}`).removeAttribute('disabled');
                }, 1000);
            },
            onCountdownEnd(currQuestion, nextQuestion) {
                if (this.trivia.questions.length == nextQuestion) {
                    this.currentQuestion = null
                    this.triviaFinished = true

                    this.sendAnswersToEval()
                } else {
                    this.showNextQuestion(currQuestion, nextQuestion)
                }
            },
            onCountdownProgress(data) {
                this.answers[this.currentQuestion].time_left = data.seconds
            },
            showNextQuestion(currQuestion, nextQuestion) {
                if (this.trivia.questions.length == nextQuestion) {
                    this.currentQuestion = null
                    this.triviaFinished = true
                    this.sendAnswersToEval()
                } else {
                    this.currentQuestion = nextQuestion
                }
            },
            sendAnswersToEval() {
                const self = this
                const data = {}
                data.trivia = this.trivia.id
                data.answers = this.answers

                axios.post('evaluate-trivia', data).then((res) => {
                    if (res.data.success) {
                        self.results = res.data.info['answers'];
                        self.achievedPoints = res.data.info['points'];
                    }
                }).catch(() => {
                  humane.error('Ha ocurrido un error al evaluar la trivia.')
                })
            },
            finishTrivia(e) {
                //e.target.setAttribute('disabled', 'disabled');
                this.disabled = true;
                window.location.href = '/trivias-grand-prix';
            }
        },
        components:{
            'countdown': VueCountdown
        }
    }
</script>
