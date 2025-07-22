<script setup>
import CommandAdd from './CommandAdd.vue'
import CommandView from './CommandView.vue'
import { useAuthStore } from '@/stores/useAuthStore'

const authStore = useAuthStore()
</script>

<template>
	<CommandView></CommandView>
	<CommandAdd v-if="authStore.isAdmin"></CommandAdd>
</template>
