<script setup>
import { ref } from 'vue'

const isOpen = ref(false)

const question = ref({
	type: 'single',
	text: '',
	options: [
		{ text: '', correct: false },
		{ text: '', correct: false }
	],
	correctAnswer: ''
})

const openModal = () => {
	isOpen.value = true
}

const closeModal = () => {
	isOpen.value = false
}

const addOption = () => {
	question.value.options.push({ text: '', correct: false })
	console.log(question)
}

const removeOption = (index) => {
	question.value.options.splice(index, 1)
}

const submit = () => {
	console.log('Отправка вопроса:', question.value)
	closeModal()
	// emit или API
}
</script>

<template>
	<div class="w-full rounded-lg shadow-1 px-8 py-7">
		<div>lists</div>
		<button @click="openModal" class="py-2.5 px-8 rounded-lg bg-green-500 text-white">
			Добавить вопрос
		</button>

		<!-- Модальное окно -->
		<div
			v-if="isOpen"
			class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
		>
			<div class="bg-white rounded-xl px-8 py-7 w-full max-w-2xl shadow-xl relative">
				<!-- Кнопка закрытия -->
				<button
					@click="closeModal"
					class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl"
				>
					&times;
				</button>

				<!-- Заголовок -->
				<div class="text-xl mb-4">Новый вопрос</div>

				<!-- Форма добавления вопроса -->
				<div class="space-y-4">
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Тип вопроса</label>
						<select v-model="question.type" class="w-full border border-gray-300 rounded-lg p-2">
							<option value="single">Один вариант</option>
							<option value="multiple">Несколько вариантов</option>
							<option value="fill-in-the-blank">Вставка пропущенного слова</option>
						</select>
					</div>

					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Текст вопроса</label>
						<input
							v-model="question.text"
							type="text"
							class="border w-full border-gray-200 bg-gray-50 rounded py-2 px-3"
							placeholder="Введите текст вопроса"
						/>
					</div>

					<!-- Варианты -->
					<div v-if="question.type === 'single' || question.type === 'multiple'">
						<label class="block text-sm font-medium text-gray-700 mb-2">Варианты ответа</label>
						<div
							v-for="(option, index) in question.options"
							:key="index"
							class="flex items-center gap-2 mb-2"
						>
							<input
								v-model="option.text"
								type="text"
								class="border w-full border-gray-200 bg-gray-50 rounded py-2 px-3"
								:placeholder="`Вариант ${index + 1}`"
							/>
							<input
								:type="question.type === 'single' ? 'radio' : 'checkbox'"
								:name="'correctOption'"
								v-model="option.correct"
								:true-value="true"
								:false-value="false"
								class="w-full w-5 h-5 text-blue-600"
							/>
							<button @click="removeOption(index)" class="text-red-500 hover:text-red-700 text-lg">
								✕
							</button>
						</div>
						<button @click="addOption" class="py-2.5 px-8 rounded-lg bg-green-500 text-white">
							+ Добавить вариант
						</button>
					</div>

					<!-- Ответ с пропущенным словом -->
					<div v-if="question.type === 'fill-in-the-blank'">
						<label class="block text-sm font-medium text-gray-700 mb-1">Правильный ответ</label>
						<input
							v-model="question.correctAnswer"
							type="text"
							class="border w-full border-gray-200 bg-gray-50 rounded py-2 px-3"
							placeholder="Введите правильный ответ"
						/>
					</div>

					<div class="pt-4">
						<button
							@click="submit"
							class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-3 rounded-lg"
						>
							Сохранить вопрос
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
