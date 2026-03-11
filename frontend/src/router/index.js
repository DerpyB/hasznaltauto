import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [
  // Kezdőlap
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/HomeView.vue')
  },
  
  // Autók listázása
  {
    path: '/cars',
    name: 'Cars',
    component: () => import('../views/CarsView.vue')
  },
  
  // Hirdetés feladása (védett - csak bejelentkezve)
  {
    path: '/sell',
    name: 'Sell',
    component: () => import('../views/SellView.vue'),
    meta: { requiresAuth: true }
  },
  
  // Auth routok
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/RegisterView.vue'),
    meta: { guestOnly: true }
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/LoginView.vue'),
    meta: { guestOnly: true }
  },
  {
    path: '/verify-email',
    name: 'VerifyEmail',
    component: () => import('../views/VerifyEmailView.vue'),
    meta: { guestOnly: true }
  },
  {
    path: '/forgot-password',
    name: 'ForgotPassword',
    component: () => import('../views/ForgotPasswordView.vue'),
    meta: { guestOnly: true }
  },
  {
    path: '/reset-password',
    name: 'ResetPassword',
    component: () => import('../views/ResetPasswordView.vue'),
    meta: { guestOnly: true }
  },
  {
    path: '/change-password',
    name: 'ChangePassword',
    component: () => import('../views/ChangePasswordView.vue'),
    meta: { requiresAuth: true }
  },
  
  // 404 - nem található oldal
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('../views/NotFoundView.vue')
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  
  // Ha védett oldal és nincs bejelentkezve
  if (to.meta.requiresAuth && !authStore.isLoggedIn) {
    next('/login');
  } 
  // Ha vendég oldal (login/register) és be van jelentkezve
  else if (to.meta.guestOnly && authStore.isLoggedIn) {
    next('/');
  } 
  else {
    next();
  }
});

export default router;