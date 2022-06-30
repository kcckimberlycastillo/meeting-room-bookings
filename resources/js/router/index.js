import { createWebHistory, createRouter } from 'vue-router'

import BookingIndex from '../components/bookings/BookingIndex'
import Login from '../components/auth/Login'
import Register from '../components/auth/Register'

const isUserLoggedIn = () => {
  return localStorage.getItem('userData')
}

const getUserData = () => JSON.parse(localStorage.getItem('userData'))

export const routes = [
    {
        path: '/login',
        name: 'auth-login',
        component: () => Login,
        meta: {
            pageTitle: 'Login',
            resource: 'Auth',
            action: 'read',
        }
    },
    {
        path: '/register',
        name: 'auth-register',
        component: () => Register,
        meta: {
            pageTitle: 'Register',
            resource: 'Auth',
            action: 'read',
        }
    },
    {
        path: '/',
        name: 'bookings',
        component: () => BookingIndex,
        meta: {
            pageTitle: 'Home',
            resource: 'bookings',
            action: 'read',
            hidePageTitle: true
        }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

router.beforeEach((to, _, next) => {
    const isLoggedIn = isUserLoggedIn()

    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isLoggedIn) {
            next({ name: 'auth-login' })
        } else {
            if (isLoggedIn) {
                let page = getUserData ? '/' : 'auth-login'
                
                next({ name: page })
            }
        }
    } else {
        next()
    }
})

export default router

