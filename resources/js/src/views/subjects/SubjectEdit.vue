<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter, useRoute } from 'vue-router'

import Button from '@/components/ui/Button.vue'

const router = useRouter()
const route = useRoute()

const id = route.params.subject_id

const name = ref('')
const description = ref('')
const image = ref(null)
const error = ref('')

const onFileChange = (event) => {
	image.value = event.target.files[0]
	console.log(event.target.files[0])
}

const fetchSubject = async () => {
	try {
		const { data } = await axios.get(`/api/subjects/${id}`)

		name.value = data.name
		description.value = data.description
		image.value = data.image || null
	} catch (err) {
		console.log(err)

		error.value = err.data?.data?.message || 'Не удалось загрузить данные'
	}
}

const updateSubject = async () => {
	try {
		const formData = new FormData()
		formData.append('name', name.value)
		formData.append('description', description.value)
		if (image.value instanceof File) {
			formData.append('image', image.value)
		}

		const response = await axios.post(`/api/subjects/edit/${id}`, formData)

		if (response.status === 200) {
			router.push('/subjects')
		}
	} catch (err) {
		error.value = err.response?.data?.message || 'Не удалось обновить предмет'
	}
}

onMounted(() => {
	fetchSubject()
})
</script>

<template>
	<div class="w-full rounded-lg shadow-1 px-8 py-7">
		<form @submit.prevent="updateSubject">
			<div v-if="error" class="mb-4 text-red-500">{{ error }}</div>
			<div class="flex gap-8">
				<div class="flex flex-col gap-8 w-1/2">
					<div class="flex flex-col">
						<label class="text-gray-500 mb-2">Название</label>
						<input
							v-model="name"
							type="text"
							class="border border-gray-200 bg-gray-50 rounded py-2 px-3"
							required
						/>
					</div>
					<div class="flex flex-col">
						<label class="text-gray-500 mb-2">Описание</label>
						<textarea
							v-model="description"
							class="border border-gray-200 bg-gray-50 rounded py-2 px-3 h-40"
							required
						></textarea>
					</div>
				</div>
				<div class="w-1/2">
					<div class="flex flex-col">
						<label class="text-gray-500 mb-2">Изображение</label>
						<input @change="onFileChange" type="file" />
						<div v-if="image" class="mt-8">
							<img
								:src="`/storage/${image}`"
								alt="Текущее изображение"
								class="w-30 h-30 object-cover rounded"
							/>
						</div>
						<div v-else class="mt-8">
							<img
								:src="`/images/default-subject.jpg`"
								alt="Текущее изображение"
								class="w-30 h-30 object-cover rounded"
							/>
						</div>
					</div>
				</div>
			</div>
			<Button type="submit" text="Обновить" />
		</form>
	</div>
</template>

<style scoped>
/* Добавьте ваши стили здесь, если необходимо */
</style>
