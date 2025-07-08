<script setup>
import { onMounted } from 'vue'

import { useSubjectsStore } from '@/stores/useSubjectStore'

import Card from '@/components/TheCard.vue'
// import LoadingCard from '@/components/skeleton/LoadingCard.vue'

const subjectsStore = useSubjectsStore()

onMounted(() => {
	subjectsStore.getSubjects()
})
</script>

<template>
	<router-view></router-view>
	<div class="grid grid-cols-3 gap-8">
		<!-- <LoadingCard v-if="subjectsStore.loading" v-for="i in subjectsStore.totalSubjects+1" :key="i" /> -->
		<Card v-for="subject in subjectsStore.subjects" :key="subject.id" :subject="subject" />
	</div>
</template>

<style lang="sass"></style>
