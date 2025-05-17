import { createRouter, createWebHistory } from 'vue-router'
import { routes } from './routes'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to, from, next) => {
  const selectedModule = localStorage.getItem('selectedModule')
  const accessToken = localStorage.getItem('accessToken')

  const isLoggedIn = !!accessToken
  const hasModule = !!selectedModule

  const publicPages = ['/login', '/register']

  const authRequired = !publicPages.includes(to.path)
  
  // check if is authenticated and stay logged in
  if (authRequired && !isLoggedIn) {
    return next('/login')
  }

  // check if is authenticated and the module is selected
  if (publicPages.includes(to.path) && isLoggedIn) {
    if (!hasModule) {
      return next('/access-control')
    }
  }

  next()
})

export default function (app) {
  app.use(router)
}
export { router }
