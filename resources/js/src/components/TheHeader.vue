<script setup>
import { watch } from 'vue'

import { useAuthStore } from '@/stores/useAuthStore'
import router from '@/router'

const authStore = useAuthStore()

const handleLogout = () => {
	authStore.logout()
}

watch(
	() => authStore.isAuthenticated,
	(val) => !val && router.push('/')
)
</script>

<template>
	<header class="flex justify-between items-center w-full h-20 shadow-1 px-10 mb-10">
		<div class="logo">
			<router-link to="/subjects">
				<img src="/public/icons/logo.svg" alt="Code Wave" />
			</router-link>
		</div>
		<div class="flex items-center gap-5">
			<div class="flex items-center gap-4">
				<img
					class="w-12 h-12 rounded-full bg-gray-30 object-cover"
					src="/public/temp-card.jpg"
					alt=""
				/>
				<div>Привет, {{ authStore.firstname }}</div>
			</div>
			<button @click="handleLogout" class="cursor-pointer">
				<img src="/public/icons/login.svg" alt="logout" />
			</button>
		</div>
	</header>
</template>
