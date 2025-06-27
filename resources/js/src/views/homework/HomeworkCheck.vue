<template>
	<div class="w-full rounded-lg shadow-1 px-8 py-7">
		<!-- Заголовок -->
		<div>
			<p class="text-gray-700">
				Студент: <strong>{{ submission.student_name }}</strong>
			</p>
			<p class="text-sm text-gray-500 mb-5">Дата отправки: {{ submission.submitted_at }}</p>
		</div>

		<!-- Текстовый ответ -->
		<div class="mb-5">
			<label class="block text-sm font-medium text-gray-700 mb-1">Ответ студента:</label>
			<div class="border border-gray-300 p-4 rounded-md bg-gray-50 whitespace-pre-wrap">
				{{ submission.text_answer || '— ответ отсутствует —' }}
			</div>
		</div>

		<!-- Прикреплённый файл -->
		<div class="mb-5" v-if="submission.file_url">
			<label class="block text-sm font-medium text-gray-700 mb-1">Файл:</label>
			<a :href="submission.file_url" target="_blank" class="text-blue-600 hover:underline">
				Скачать файл
			</a>
		</div>

		<!-- Статус -->
		<div>
			<StatusBadge :status="submission.status" />
		</div>

		<!-- Комментарий преподавателя -->
		<div class="mb-5">
			<label class="block text-sm font-medium text-gray-700 mb-1">Комментарий преподавателя:</label>
			<textarea
				v-model="comment"
				class="w-full p-3 border border-gray-300 rounded-lg resize-y min-h-[80px]"
				placeholder="Напишите комментарий..."
			/>
		</div>

		<!-- Кнопки -->
		<div class="flex gap-3">
			<button @click="approve" class="py-2.5 px-8 rounded-lg bg-green-500 text-white">
				Принять
			</button>
			<button @click="reject" class="py-2.5 px-8 rounded-lg bg-red-500 text-white">
				Отклонить
			</button>
		</div>
	</div>
</template>

<script setup>
import { ref } from 'vue'
// import StatusBadge from './StatusBadge.vue'

// Пример полученного ответа студента
const submission = {
	id: 12,
	student_name: 'Иван Петров',
	submitted_at: '2025-06-09 10:15',
	text_answer:
		'Мой ответ на задание: я реализовал компонент комментариев с использованием Vue и Laravel.',
	file_url: '/storage/homework/ivan_petrov_task1.pdf',
	status: 'submitted' // или "reviewed", "rejected"
}

const comment = ref('')

const approve = () => {
	fetch(`/api/submissions/${submission.id}/approve`, {
		method: 'POST',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify({ comment: comment.value })
	})
		.then((res) => res.json())
		.then(() => {
			alert('Ответ принят.')
			submission.status = 'reviewed'
		})
		.catch(() => alert('Ошибка при отправке.'))
}

const reject = () => {
	fetch(`/api/submissions/${submission.id}/reject`, {
		method: 'POST',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify({ comment: comment.value })
	})
		.then((res) => res.json())
		.then(() => {
			alert('Ответ отклонён.')
			submission.status = 'rejected'
		})
		.catch(() => alert('Ошибка при отправке.'))
}
</script>
