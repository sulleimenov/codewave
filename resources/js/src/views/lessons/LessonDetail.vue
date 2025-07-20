<script setup>
import { ref, onMounted, watch, onActivated } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const subject_id = ref(route.params.subject_id)
const topic_id = ref(route.params.topic_id)
const name = ref('')
const objective = ref('')
const tasks = ref('')
const description = ref('')
const date = ref('')
const error = ref('')

const getLesson = async () => {
	const url = `api/subjects/${subject_id.value}/topic/${topic_id.value}`
	console.log('Request URL:', url)
	try {
		const { data } = await axios.get(url)
		subject_id.value = data.subject_id
		name.value = data.name
		objective.value = data.objective
		tasks.value = data.tasks
		description.value = data.description
		date.value = data.created_at
	} catch (err) {
		console.error('Error:', err)
		error.value = err.response?.data?.message || 'Не удалось загрузить данные'
	}
}

// Отслеживание изменений маршрута
watch(
	() => [route.params.subject_id, route.params.topic_id],
	([newSubjectId, newTopicId]) => {
		subject_id.value = newSubjectId
		topic_id.value = newTopicId
		getLesson()
	}
)

onMounted(getLesson)
onActivated(getLesson)
</script>

<template>
	<div class="w-full rounded-lg shadow-1 px-8 py-7">
		<div v-if="error" class="text-red-500 mb-4">{{ error }}</div>
		<div class="flex gap-8">
			<div class="flex flex-col gap-8 w-1/2">
				<div class="flex flex-col">
					<label class="text-gray-500 mb-2">Тема урока</label>
					<span>{{ name }}</span>
				</div>
				<div class="flex flex-col">
					<label class="text-gray-500 mb-2">Цель</label>
					<span>{{ objective }}</span>
				</div>
				<div class="flex flex-col">
					<label class="text-gray-500 mb-2">Задачи</label>
					<span>{{ tasks }}</span>
				</div>
			</div>
			<div class="flex flex-col w-1/2 gap-8">
				<div class="flex flex-col">
					<label class="text-gray-500 mb-2">Описание</label>
					<span>{{ description }}</span>
				</div>
				<div class="flex flex-col">
					<label class="text-gray-500 mb-2">Дата занятия</label>
					<span>{{ date }}</span>
				</div>
			</div>
		</div>
	</div>
</template>
