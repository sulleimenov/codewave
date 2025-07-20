<script setup>
import CommandAdd from './CommandAdd.vue'
import CommandView from './CommandView.vue'
import { useAuthStore } from '@/stores/useAuthStore'

const authStore = useAuthStore()
</script>

<template>
	<CommandView v-if="!authStore.isAdmin"></CommandView>
	<CommandAdd v-else></CommandAdd>
</template>
