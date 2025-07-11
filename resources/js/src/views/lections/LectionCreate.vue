<template>
	<div class="max-w-4xl mx-auto mt-6 p-4 bg-white rounded shadow">
		<MdEditor v-model="lectionText" language="ru-RU" style="height: 300px" class="mb-4" />
		<button
			@click="submit"
			class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700"
			:disabled="saving"
		>
			{{ saving ? 'Сохранение...' : 'Сохранить лекцию' }}
		</button>
		<div v-if="error" class="text-red-500 mt-4">{{ error }}</div>
	</div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { MdEditor } from 'md-editor-v3'
import 'md-editor-v3/lib/style.css'
import { useAuthStore } from '@/stores/useAuthStore'

const authStore = useAuthStore()
const route = useRoute()
const lectionText = ref('')
const saving = ref(false)
const error = ref(null)

const submit = async () => {
	if (!lectionText.value.trim()) {
		error.value = 'Введите текст лекции'
		return
	}

	try {
		saving.value = true
		await axios.post(
			'/api/lections', // Add /api/ prefix
			{
				subject_id: route.params.subject_id,
				content: lectionText.value
			},
			{
				headers: {
					Authorization: `Bearer ${authStore.token}`,
					'Content-Type': 'application/json'
				}
			}
		)
		error.value = null
		alert('Лекция успешно сохранена!')
	} catch (err) {
		error.value = err.response?.data?.message || 'Ошибка при сохранении'
	} finally {
		saving.value = false
	}
}
</script>
