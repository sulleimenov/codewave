<script setup>
import { computed } from 'vue'
import MarkdownIt from 'markdown-it'

const props = defineProps({
	source: {
		type: String,
		required: true
	}
})

const md = new MarkdownIt()
const rendered = computed(() => md.render(props.source))
</script>

<template>
	<div v-html="rendered"></div>
</template>
