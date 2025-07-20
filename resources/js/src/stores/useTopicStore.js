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
		async getTopics(subjectId, userId = null) {
			try {
				this.loading = true
				const params = {}
				if (userId) {
					params.user_id = userId
				}

				const data = await axios.get(`/api/subjects/${subjectId}/topics`, { params })
				if (data.status === 200) {
					this.topics = data.data
				}
			} catch (error) {
				console.log(error)
				return null
			} finally {
				this.loading = false
				return null
			}
		},
		async deleteTopic(id) {
			try {
				const { data } = await axios.delete(`/api/topics/${id}`)
				this.getTopics()
			} catch (error) {
				console.log(error)
				return null
			}
		}
	}
})
