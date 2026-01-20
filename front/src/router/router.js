import { createRouter, createWebHistory } from 'vue-router'
import axios from '../libs/axios'

const routes = [
  {
    path: '/',
    name: 'PortfolioHome',
    component: () => import('../views/PortfolioHome.vue'),
  },
  {
    path: '/home',
    name: 'Home',
    component: () => import('../views/HomeView.vue'),
  },
  {
    path: '/cart',
    name: 'Cart',
    component: () => import('../views/CartView.vue'),
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: () => import('../views/CheckoutView.vue'),
  },
  {
    path: '/complete',
    name: 'OrderComplete',
    component: () => import('../views/OrderCompleteView.vue'),
  },

  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: () => import('../views/admin/Login.vue'),
  },

  {
    path: '/admin',
    component: () => import('../layouts/AdminLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'AdminDashboard',
        component: () => import('../views/admin/Dashboard.vue'),
      },
      {
        path: 'order',
        name: 'AdminOrderList',
        component: () => import('../views/admin/OrderList.vue'),
      },
      {
        path: 'order/:id',
        name: 'AdminOrderDetail',
        component: () => import('../views/admin/OrderDetail.vue'),
        props: true,
      },
      {
        path: 'products',
        name: 'AdminProducts',
        component: () => import('../views/admin/ProductList.vue'),
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

/*
管理画面は未ログイン時にログインへリダイレクト
*/
router.beforeEach(async (to) => {
  const requiresAuth = to.matched.some(
    route => route.meta.requiresAuth
  )

  if (!requiresAuth) return true

  try {
    await axios.get('/admin/me')
    return true
  } catch {
    return { name: 'AdminLogin' }
  }
})

export default router

