import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/useAuthStore'

import Login from '@/views/auth/LoginView.vue'
import SubjectList from '@/views/subjects/SubjectList.vue'
import SubjectCreate from '@/views/subjects/SubjectCreate.vue'
import SubjectEdit from '@/views/subjects/SubjectEdit.vue'
import LessonList from '@/views/lessons/LessonList.vue'
import LessonDetail from '@/views/lessons/LessonDetail.vue'
import LessonCreate from '@/views/lessons/LessonCreate.vue'
import CriterionView from '@/views/сriterion/CriterionView.vue'
import TestingCreate from '@/views/testing/TestingCreate.vue'
import TestingView from '@/views/testing/TestingView.vue'
import HomeworkView from '@/views/homework/HomeworkView.vue'
import HomeworkCheck from '@/views/homework/HomeworkCheck.vue'

const routes = [
	{ path: '/', name: 'login', component: Login },
	{
		path: '/subjects',
		name: 'subjects',
		component: SubjectList,
		meta: { requiresAuth: true, title: 'Список модулей' }
	},
	{
		path: '/lessons',
		name: 'lessons',
		component: LessonList,
		meta: { requiresAuth: true, title: 'Список уроков' }
	},
	{
		path: '/homework',
		name: 'homework',
		component: HomeworkView,
		meta: { requiresAuth: true, title: 'Список уроков' }
	},
	{
		path: '/subjects/create',
		name: 'subject_create',
		component: SubjectCreate,
		meta: { requiresAuth: true, title: 'Добавление модуля' }
	},
	{
		path: '/subjects/edit/:subject_id',
		name: 'subject_edit',
		component: SubjectEdit,
		meta: { requiresAuth: true, title: 'Редактирование модуля' }
	},
	{
		path: '/subjects/:subject_id',
		name: 'subject',
		component: LessonList,
		meta: { requiresAuth: true, title: 'Список тем' }
	},
	{
		path: '/subjects/:subject_id/topic/:topic_id/description',
		name: 'lesson_detail_description',
		component: LessonDetail,
		meta: { requiresAuth: true, title: 'Описание урока' }
	},
	{
		path: '/subjects/:subject_id/topic/create',
		name: 'lesson_create',
		component: LessonCreate,
		meta: { requiresAuth: true, title: 'Добавление темы' }
	},
	{
		path: '/subjects/:subject_id/topic/:topic_id/criterion',
		name: 'lesson_criterion',
		component: CriterionView,
		meta: { requiresAuth: true, title: 'Критерий оценивания' }
	},
	{
		path: '/subjects/:subject_id/topic/:topic_id/testing',
		name: 'lesson_testing',
		component: () => {
			const authStore = useAuthStore()
			return authStore.isAdmin ? TestingCreate : TestingView
		},
		meta: { requiresAuth: true, title: 'Тестирование' }
	},
	{
		path: '/subjects/:subject_id/topic/:topic_id/homework',
		name: 'homework_view',
		component: HomeworkView,
		meta: { requiresAuth: true, title: 'Домашнее задание' }
	},
	{
		path: '/subjects/:subject_id/topic/:topic_id/homework/check',
		name: 'homework_check',
		component: HomeworkCheck,
		meta: { requiresAuth: true, title: 'Домашнее задание' }
	}
]

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes
})

router.beforeEach((to, from, next) => {
	const authStore = useAuthStore()
	if (to.meta.requiresAuth && !authStore.isAuthenticated) {
		next({ name: 'login' })
	} else {
		next()
	}
})

export default router
