<script setup>
defineProps({
	type: {
		type: String,
	},
	text: {
		type: String,
	}
})
</script>

<template>
	<button :type="type" class="bg-green-500 ml-auto mr-0 text-white rounded mt-5 py-2.5 px-8">{{ text }}</button>
</template>