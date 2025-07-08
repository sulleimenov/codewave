import { defineStore } from 'pinia'
import axios from 'axios'

export const useSubjectsStore = defineStore('subjects', {
	state: () => ({
		loading: false,
		subjects: []
	}),
	getters: {
		totalSubjects: (state) => state.subjects.length
	},
	actions: {
		async getSubjects() {
			try {
				this.loading = true

				const { data } = await axios.get('/api/subjects')
				this.subjects = data
			} catch (error) {
				console.log(error)
			} finally {
				this.loading = false
			}
		},
		async deleteSubject(id) {
			try {
				const { data } = await axios.delete(`/api/subjects/${id}`)
				this.getSubjects()
			} catch (error) {
				console.log(error)
			}
		}
	}
})
