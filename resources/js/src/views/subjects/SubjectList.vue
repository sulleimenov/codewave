<script setup>
import { onMounted } from 'vue'

import { useSubjectsStore } from '@/stores/useSubjectStore'

import Card from '@/components/TheCard.vue'
// import LoadingCard from '@/components/skeleton/LoadingCard.vue'

const subjectsStore = useSubjectsStore()
import { useRouter } from 'vue-router'
const router = useRouter()
onMounted(() => {
	subjectsStore.getSubjects()
})
</script>

<template>
	<router-view></router-view>
	<button
		@click="router.push('/subjects_create')"
		class="p-2 px-6 text-white rounded-xl bg-blue-500 mb-4"
	>
		Create
	</button>

	<div class="grid grid-cols-3 gap-8">
		<!-- <LoadingCard v-if="subjectsStore.loading" v-for="i in subjectsStore.totalSubjects+1" :key="i" /> -->
		<Card v-for="subject in subjectsStore.subjects" :key="subject.id" :subject="subject" />
	</div>
</template>

<style lang="sass"></style>
