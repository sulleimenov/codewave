<script setup>
import Link from '@/components/ui/Link.vue'
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const subjectId = computed(() => route.params.subject_id || null)
const topicId = computed(() => route.params.topic_id || null)

const isSubjectsPage = computed(() => route.path === '/subjects')
const isTopicsPage = computed(() => /^\/subjects\/\d+$/.test(route.path))
const isTopicDetailPage = computed(() => /^\/subjects\/\d+\/topic\/\d+/.test(route.path))
</script>

<template>
	<div class="flex gap-3">
		<Link v-admin to="/subjects/create" v-if="isSubjectsPage" text="Добавить модуль" />
		<Link
			v-admin
			:to="`/subjects/${subjectId}/topic/create`"
			v-if="isTopicsPage"
			text="Добавить тему"
		/>
		<Link
			:to="`/subjects/${subjectId}/topic/${topicId}/lection_show`"
			v-if="isTopicDetailPage && route.name !== 'lection_show'"
			text="Просмотреть лекцию"
		/>
	</div>
</template>
