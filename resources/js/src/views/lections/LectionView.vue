<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useTopicsStore } from '@/stores/useTopicStore'
import { useAuthStore } from '@/stores/useAuthStore'
import Markdown from 'vue3-markdown-it'

const route = useRoute()
const router = useRouter()
const topicsStore = useTopicsStore()
const authStore = useAuthStore()
const testContent = ref(null)
const subjectId = route.params.subject_id
const topicId = route.params.topic_id

onMounted(async () => {
	if (!['admin', 'teacher', 'student'].includes(authStore.role)) {
		router.push(`/subjects/${subjectId}`)
		return
	}
	try {
		testContent.value = await topicsStore.getTopic(subjectId, topicId) // Adjust API call
	} catch (error) {
		console.error('Error fetching test content:', error)
	}
})
</script>

<template>
	<div v-if="testContent" class="w-full rounded-lg shadow-1 px-8 py-7">
		<h2 class="text-2xl font-bold mb-4">{{ testContent.name }} - Тест</h2>
		<p class="text-gray-600 mb-4">Дата: {{ testContent.date }}</p>
		<Markdown :source="testContent.description" />
	</div>
</template>
