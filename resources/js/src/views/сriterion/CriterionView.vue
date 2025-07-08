<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
</script>

<template>
	<div class="w-full rounded-lg shadow-1 px-8 py-7">Добавить критерии</div>
</template>
