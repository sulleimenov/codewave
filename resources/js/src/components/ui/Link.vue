<script setup>
defineProps({
	to: {
		type: String
	},
	text: {
		type: String
	}
})
</script>

<template>
	<router-link :to="to" class="py-2.5 px-8 rounded-lg bg-blue-500 text-white">
		{{ text }}
	</router-link>
</template>
