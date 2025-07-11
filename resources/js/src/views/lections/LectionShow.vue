<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/useAuthStore'
import Markdown from 'vue3-markdown-it'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const lection = ref(null)
const subjectId = route.params.subject_id
const topicId = route.params.topic_id
const isLoading = ref(false)
const error = ref(null)

onMounted(async () => {
	if (!['admin', 'teacher', 'student'].includes(authStore.role)) {
		router.push(`/subjects/${subjectId}`)
		return
	}
	try {
		isLoading.value = true
		const response = await axios.get(`/api/subjects/${subjectId}/topics/${topicId}`)
		lection.value = response.data
	} catch (err) {
		error.value = err.message
		console.error('Error fetching lection:', err)
	} finally {
		isLoading.value = false
	}
})
</script>

<template>
	<div v-if="isLoading" class="w-full text-center">Загрузка...</div>
	<div v-else-if="error" class="text-red-500">Ошибка: {{ error }}</div>
	<div v-else-if="lection" class="w-full rounded-lg shadow-1 px-8 py-7">
		<h2 class="text-2xl font-bold mb-4">{{ lection.name }} - Лекция</h2>
		<p class="text-gray-600 mb-4">Дата: {{ lection.date }}</p>
		<Markdown v-if="lection.description" :source="lection.description" />
		<p v-else>Описание отсутствует</p>
		<div class="mb-4">
			<h3 class="text-xl font-bold mb-2">Материалы лекции</h3>
			<p class="text-gray-600">Дополнительные материалы для лекции (если доступны).</p>
		</div>
	</div>
	<div v-else>Лекция не найдена</div>
</template>
