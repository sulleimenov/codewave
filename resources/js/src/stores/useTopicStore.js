import { defineStore } from 'pinia'
import axios from 'axios'

export const useTopicsStore = defineStore('topics', {
	state: () => ({
		loading: false,
		topics: []
	}),
	getters: {
		totalTopics: (state) => state.topics.length
	},
	actions: {
		async getTopic(subjectId, topicId) {
			try {
				const response = await axios.get(`/api/subjects/${subjectId}/topics/${topicId}`, {
					headers: {
						Authorization: `Bearer ${localStorage.getItem('authToken')}`
					}
				})
				return response.data.topic
			} catch (error) {
				console.error('Error fetching topic:', error)
				return null
			}
		},
		async getTopics(subjectId) {
			try {
				this.loading = true

				const { data } = await axios.get(`/api/subjects/${subjectId}/topics`)
				this.topics = data
			} catch (error) {
				console.log(error)
			} finally {
				this.loading = false
			}
		},
		async deleteTopic(id) {
			try {
				const { data } = await axios.delete(`/api/topics/${id}`)
				this.getTopics()
			} catch (error) {
				console.log(error)
			}
		}
	}
})
