Быскшзе ыуегзЮ
<script setup>
import Link from '@/components/ui/Link.vue'
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const id = computed(() => route.params.subject_id || null)

const isSubjectsPage = computed(() => route.path === '/subjects')
const isTopicsPage = computed(() => /^\/subjects\/\d+$/.test(route.path))
</script>

<template>
	<div class="flex gap-3">
		<Link v-admin to="/subjects/create" v-if="isSubjectsPage" text="Добавить" />
		<Link v-admin :to="`/subjects/${id}/topic/create`" v-if="isTopicsPage" text="Добавить тему" />
		<Link v-admin :to="`/subjects/${id}/topic/create`" v-if="isTopicsPage" text="Добавить" />
	</div>
</template>
