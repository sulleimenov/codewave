<script setup>
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const isThemePage = computed(
	() =>
		route.name === 'lesson_detail_description' ||
		route.name === 'lesson_criterion' ||
		route.name === 'lesson_testing' ||
		route.name === 'homework_view' ||
		route.name === 'homework_check'
)
const isModulesPage = computed(() => route.name === 'Модули')

const completedStages = ref({
	'Критерий оценивания': false,
	'Домашнее задание': true,
	Лекция: false,
	Тестирование: true,
	'Практическое задание': false,
	Рефлексия: false
})

const links = computed(() => {
	const commonLinks = [
		{ to: '/subjects', icon: 'link.svg', label: 'Модули', extraClass: 'filter-gray-400' },
		{ to: '/', icon: 'book.svg', label: 'Книги' },
		{ to: '/', icon: 'book.svg', label: 'Отработки' },
		{ to: '/', icon: 'book.svg', label: 'Как это сделано?' }
	]

	if (isModulesPage.value) {
		return commonLinks
	}

	if (isThemePage.value) {
		return [
			{
				to: {
					name: 'lesson_detail_description',
					params: {
						subject_id: route.params.subject_id,
						topic_id: route.params.topic_id
					}
				},
				icon: 'descr.svg',
				label: 'Описание урока'
			},
			{
				to: {
					name: 'lesson_criterion',
					params: {
						subject_id: route.params.subject_id,
						topic_id: route.params.topic_id
					}
				},
				icon: 'pool.svg',
				label: 'Критерий оценивания',
				extraClass: completedStages.value['Критерий оценивания'] ? '' : 'text-gray-400 saturate-0'
			},
			{
				to: 'homework',
				icon: 'homework.svg',
				label: 'Домашнее задание',
				extraClass: completedStages.value['Домашнее задание'] ? '' : 'text-gray-400 saturate-0'
			},
			{
				to: '#',
				icon: 'book.svg',
				label: 'Лекция',
				extraClass: completedStages.value['Лекция'] ? '' : 'text-gray-400 saturate-0'
			},
			{
				to: 'testing',
				icon: 'test.svg',
				label: 'Тестирование',
				extraClass: completedStages.value['Тестирование'] ? '' : 'text-gray-400 saturate-0'
			},
			{
				to: '#',
				icon: 'practical.svg',
				label: 'Практическое задание',
				extraClass: completedStages.value['Практическое задание'] ? '' : 'text-gray-400 saturate-0'
			},
			{
				to: '#',
				icon: 'pool.svg',
				label: 'Рефлексия',
				extraClass: completedStages.value['Рефлексия'] ? '' : 'text-gray-400 saturate-0'
			}
		]
	}

	return commonLinks
})
</script>

<template>
	<aside class="w-1/4 grow shadow-1 h-max rounded-lg py-3">
		<router-link
			v-for="link in links"
			:key="link.label"
			:to="link.to"
			class="flex gap-2 py-3 px-6 hover:bg-gray-50 transition-colors"
			:class="link.extraClass"
		>
			<img :src="`/icons/${link.icon}`" :alt="`${link.label}`" />
			<span>{{ link.label }}</span>
		</router-link>
	</aside>
</template>
