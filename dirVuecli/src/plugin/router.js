import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store'

Vue.use(VueRouter);

let routes = [
    {
        path: '/',
        name: '首页',
        component: () => import('../views/site/Home.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/about',
        name: 'about',
        component: () => import('../views/site/About.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/test',
        name: 'test',
        component: () => import('../views/site/Test.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        name: '登录',
        component: () => import('../views/site/Login.vue'),
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/login-by-email',
        name: '邮箱登录',
        component: () => import('../views/site/LoginByEmail.vue'),
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/signup',
        name: '注册',
        component: () => import('../views/site/Signup.vue'),
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/reset-password',
        name: '重置密码',
        component: () => import('../views/site/ResetPassword.vue'),
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/user/center',
        name: '个人中心',
        component: () => import('../views/user/Center.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/profile/:id',
        name: '用户信息',
        component: () => import('../views/user/Profile.vue'),
        meta: {
            requiresAuth: true
        }
    }
];

let router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth){
        if (!store.getters.isAuth){
            next('/login');
            return;
        }
    } else{
        if (store.getters.isAuth){
            next('/');
            return;
        }
    }
    next();
});

export default router
