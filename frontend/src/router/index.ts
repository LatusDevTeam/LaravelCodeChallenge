import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/jokes',
      component: () => import('@/features/Jokes.vue')
    }
  ],
})

export default router
