<template>
	<div class="w-full rounded-lg shadow-1 px-8 py-7">
		<div class="mb-4">
			<div class="flex justify-between items-center text-gray-500">
				<span>Вопрос {{ currentIndex + 1 }} из {{ questions.length }}</span>
				<span>{{ progressPercent }}%</span>
			</div>
			<div class="w-full bg-gray-200 rounded-full h-2 mt-2 mb-6">
				<div
					class="bg-green-400 h-2 rounded-full transition-all duration-300"
					:style="{ width: progressPercent + '%' }"
				></div>
			</div>
		</div>

		<h2 class="text-xl font-semibold text-gray-800 mb-4">
			{{ currentQuestion.question }}
		</h2>

		<div class="space-y-3 mb-6">
			<label
				v-for="(option, index) in currentQuestion.options"
				:key="index"
				class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-gray-50"
				:class="{
					'border-indigo-500 bg-indigo-50': selectedAnswer === option
				}"
			>
				<input type="radio" class="accent-indigo-500" :value="option" v-model="selectedAnswer" />
				<span>{{ option }}</span>
			</label>
		</div>

		<div class="flex justify-end">
			<button
				class="bg-indigo-600 text-white px-6 py-2 rounded-xl hover:bg-indigo-700 disabled:bg-gray-300 transition-colors"
				:disabled="!selectedAnswer"
				@click="nextQuestion"
			>
				{{ isLastQuestion ? 'Завершить' : 'Далее' }}
			</button>
		</div>
	</div>
</template>

<script setup>
import { ref, computed } from 'vue'

const questions = ref([
	{
		question: 'Какой язык используется для стилизации веб-страниц?',
		options: ['HTML', 'Python', 'CSS', 'SQL'],
		correct: 'CSS'
	},
	{
		question: 'Какой результат будет у выражения 2 + "2" в JavaScript?',
		options: ['4', '"22"', 'NaN', 'undefined'],
		correct: '"22"'
	},
	{
		question: 'Какой тег используется для заголовка первого уровня в HTML?',
		options: ['<head>', '<h1>', '<header>', '<title>'],
		correct: '<h1>'
	}
])

const currentIndex = ref(0)
const selectedAnswer = ref(null)
const answers = ref([])

const currentQuestion = computed(() => questions.value[currentIndex.value])
const isLastQuestion = computed(() => currentIndex.value === questions.value.length - 1)
const progressPercent = computed(() =>
	Math.round(((currentIndex.value + 1) / questions.value.length) * 100)
)

function nextQuestion() {
	answers.value[currentIndex.value] = selectedAnswer.value

	if (isLastQuestion.value) {
		console.log('Тест завершён:', answers.value)
		// Здесь можно будет вызвать emit('finish', answers.value) или перейти на другой экран
	} else {
		currentIndex.value++
		selectedAnswer.value = answers.value[currentIndex.value] || null
	}
}
</script>
