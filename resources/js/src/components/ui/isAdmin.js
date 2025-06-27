// directives/adminOnly.js
import { watch } from 'vue'
import { useAuthStore } from '../../stores/useAuthStore' // Убедитесь, что путь правильный

export default {
	// Жизненный цикл mounted
	mounted(el) {
		const authStore = useAuthStore()

		// Скрываем элемент, если пользователь не администратор
		const updateDisplay = () => {
			el.style.display = authStore.isAdminPanel ? '' : 'none'
		}

		// Вызов функции при монтировании
		updateDisplay()

		// Подписка на изменения в хранилище
		const stopWatching = watch(() => authStore.isAdminPanel, updateDisplay)

		// Очищаем подписку при размонтировании
		el._onUnmount = () => {
			stopWatching()
		}
	},

	// Очищаем подписку при удалении элемента
	unmounted(el) {
		if (el._onUnmount) {
			el._onUnmount()
		}
	}
}
