import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/authStore';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue'),
      meta: { requiresGuest: true }, // Only accessible to guests
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { requiresGuest: true }, // Only accessible to guests
    },
    {
      path: '/',
      name: 'app',
      component: () => import('../views/MainView.vue'),
    },
    {
      path: '/vue-dashboard',
      name: 'vue-dashboard',
      component: () => import('../views/MainView.vue'),
      meta: { requiresAuth: true }, // Requires authentication
      beforeEnter: (to, from, next) => {
        const authStore = useAuthStore();
        if (authStore.isAuthenticated()) {
          next(); // Allow navigation if authenticated
        } else {
          next({ name: 'login' }); // Redirect to login if not authenticated
        }
      },
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('../views/ProfileView.vue'),
      meta: { requiresAuth: true }, // Requires authentication
    },
  ],
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  // Check if route requires authentication
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // Redirect to login if not authenticated
    if (!authStore.isAuthenticated()) {
      next({ name: 'login' });
    } else {
      next();
    }
  } else if (to.matched.some(record => record.meta.requiresGuest)) {
    // Redirect to dashboard if already authenticated
    if (authStore.isAuthenticated()) {
      next({ name: 'vue-dashboard' });
    } else {
      next();
    }
  } else {
    next(); // Always call next() in beforeEach
  }
});

export default router;
