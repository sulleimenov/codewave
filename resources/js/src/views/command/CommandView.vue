<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/useAuthStore'
import axios from 'axios'

const route = useRoute()
const authStore = useAuthStore()
const command = ref(null)
const error = ref(null)
const isLoading = ref(false)
const coins = ref(1000) // Начальное количество монет

const shopItems = ref([
	{
		id: 'standard_bars',
		name: 'Стандартный барс',
		price: 0,
		image: '/images/standard_bars.png',
		owned: true
	},
	{
		id: 'golden_bars',
		name: 'Золотой барс',
		price: 300,
		image: '/images/golden_bars.png',
		owned: false
	},
	{
		id: 'silver_bars',
		name: 'Серебряный барс',
		price: 200,
		image: '/images/silver_bars.png',
		owned: false
	},
	{
		id: 'epic_bars',
		name: 'Эпический барс',
		price: 500,
		image: '/images/epic_bars.png',
		owned: false
	},
	{
		id: 'legendary_bars',
		name: 'Легендарный барс',
		price: 1000,
		image: '/images/legendary_bars.png',
		owned: false
	}
])

const fetchCommand = async () => {
	try {
		const response = await axios.get(
			`/api/subjects/${route.params.subject_id}/topic/${route.params.topic_id}/command`
		)
		command.value = response.data

		// Обновляем статус owned для купленных барсов
		shopItems.value.forEach((item) => {
			if (command.value.link === item.image) {
				item.owned = true
			}
		})
	} catch (err) {
		error.value = 'Ошибка при загрузке команды'
		console.error(err)
	}
}

const buyAndUpgradePhoto = async (item) => {
	if (authStore.role !== 'admin' && coins.value < item.price) {
		error.value = 'Недостаточно монет для покупки'
		return
	}

	if (item.owned) {
		error.value = 'Этот барс уже куплен'
		return
	}

	isLoading.value = true
	error.value = null
	try {
		const response = await axios.post(`/api/commands/${command.value.id}/upgrade-photo`, {
			type: item.id
		})
		command.value = response.data

		// Обновляем состояние магазина
		shopItems.value.forEach((shopItem) => {
			if (shopItem.id === item.id) {
				shopItem.owned = true
			}
		})

		// Вычитаем стоимость, если не админ
		if (authStore.role !== 'admin') {
			coins.value -= item.price
		}
	} catch (err) {
		error.value = 'Ошибка при покупке барса'
		console.error(err)
	} finally {
		isLoading.value = false
	}
}

onMounted(fetchCommand)
</script>

<template>
	<div class="p-6">
		<h1 class="text-2xl font-bold mb-4">Команда</h1>

		<div v-if="authStore.role !== 'admin'" class="mb-4 p-3 bg-yellow-100 rounded-lg">
			<div class="flex items-center">
				<span class="font-semibold">Ваши монеты:</span>
				<span class="ml-2 px-3 py-1 bg-yellow-200 rounded-full">{{ coins }}</span>
			</div>
		</div>

		<div v-if="error" class="text-red-500 mb-4">
			{{ error }}
		</div>

		<div v-if="command" class="bg-white rounded-lg shadow p-4 mb-6">
			<div class="flex flex-col md:flex-row gap-6">
				<div class="flex-1">
					<h2 class="text-xl font-bold mb-2">Текущий барс команды</h2>
					<img
						:src="command.link"
						:alt="`${command.link}, Команда ${command.id}`"
						class="mb-4 max-w-xs rounded border-4 border-blue-200"
					/>
				</div>

				<div class="flex-1">
					<h2 class="text-xl font-bold mb-4">Магазин барсов</h2>
					<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
						<div
							v-for="item in shopItems"
							:key="item.id"
							class="border rounded-lg p-3 hover:shadow-md transition-shadow"
							:class="{
								'border-green-300 bg-green-50': item.owned,
								'border-gray-200': !item.owned,
								'ring-2 ring-blue-400': command.link === item.image
							}"
						>
							<img :src="item.image" :alt="item.name" class="w-full h-32 object-contain mb-2" />
							<h3 class="font-semibold">{{ item.name }}</h3>
							<div class="flex justify-between items-center mt-2">
								<span v-if="item.price > 0" class="text-yellow-600 font-medium">
									{{ item.price }} монет
								</span>
								<span v-else class="text-gray-500">Бесплатно</span>

								<button
									@click="buyAndUpgradePhoto(item)"
									:disabled="
										isLoading || (authStore.role !== 'admin' && (coins < item.price || item.owned))
									"
									class="px-3 py-1 text-sm font-medium rounded"
									:class="{
										'bg-blue-600 text-white hover:bg-blue-700': !item.owned,
										'bg-gray-200 text-gray-600 cursor-not-allowed': item.owned,
										'bg-green-100 text-green-800': command.link === item.image
									}"
								>
									{{ command.link === item.image ? 'Активен' : item.owned ? 'Куплен' : 'Купить' }}
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="mt-6 pt-4 border-t">
				<h2 class="text-lg font-semibold">Лидер команды</h2>
				<p class="mb-4">{{ command.leader?.firstname }} {{ command.leader?.lastname }}</p>

				<h2 class="text-lg font-semibold">Участники</h2>
				<ul class="list-disc pl-5">
					<li v-for="member in command.members" :key="member.id">
						{{ member.firstname }} {{ member.lastname }}
					</li>
				</ul>
			</div>
		</div>

		<div v-else-if="!error" class="text-gray-500">Загрузка...</div>
	</div>
</template>
