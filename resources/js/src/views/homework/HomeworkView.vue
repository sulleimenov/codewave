<template>
	<div class="w-full rounded-lg shadow-1 px-8 py-7">
		<div class="mb-5">
			<p class="text-gray-700">{{ assignment.description }}</p>
		</div>

		<div class="mb-5">
			<label class="block text-sm font-medium text-gray-700 mb-1">Текстовый ответ</label>
			<textarea
				v-model="textAnswer"
				class="w-full p-3 border border-gray-300 rounded-lg resize-y min-h-[100px]"
				placeholder="Введите ваш ответ..."
			/>
		</div>

		<div class="mb-5">
			<label class="block text-sm font-medium text-gray-700 mb-1">Прикрепить файл</label>
			<input
				type="file"
				@change="handleFileUpload"
				class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
			/>
			<div v-if="fileName" class="mt-1 text-gray-600 text-sm">Файл: {{ fileName }}</div>
		</div>

		<div>
			<button @click="submit" class="py-2.5 px-8 rounded-lg bg-blue-500 text-white">
				Отправить задание
			</button>
		</div>
	</div>
</template>

<script setup>
import { ref } from 'vue'

const assignment = {
	id: 1,
	description:
		'Реализуйте простую систему комментариев для веб-приложения. Пользователь должен иметь возможность просматривать список комментариев, оставлять новый комментарий и удалять свои. У каждого комментария должно быть имя пользователя, текст и дата создания.'
}

const textAnswer = ref('')
const selectedFile = ref(null)
const fileName = ref('')

const handleFileUpload = (event) => {
	const file = event.target.files[0]
	if (file) {
		selectedFile.value = file
		fileName.value = file.name
	}
}

const submit = () => {
	const formData = new FormData()
	formData.append('text_answer', textAnswer.value)
	if (selectedFile.value) {
		formData.append('file', selectedFile.value)
	}

	// Пример отправки запроса
	fetch(`/api/homework/${assignment.id}/submit`, {
		method: 'POST',
		body: formData
	})
		.then((res) => res.json())
		.then((data) => {
			alert('Ответ отправлен успешно!')
		})
		.catch((err) => {
			console.error(err)
			alert('Ошибка при отправке ответа.')
		})
}
</script>

<style scoped>
/* Можно добавить стили, если нужно */
</style>
