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
    },
    {
        path: '/user/ru-men-apply',
        name: '入门申请',
        component: () => import('../views/user/RuMenApply.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user/article-publish',
        name: '发布文章',
        component: () => import('../views/user/ArticlePublish.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/article/list',
        name: '文章',
        component: () => import('../views/article/List.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/article/info/:id/:isUpdate/:from',
        name: '文章',
        component: () => import('../views/article/Info.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/tag/my-circle',
        name: '圈子',
        component: () => import('../views/tag/MyCircle.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/tag/add-circle',
        name: '创建圈子',
        component: () => import('../views/tag/AddCircle.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/tag/info-circle/:id',
        name: '圈内文章',
        component: () => import('../views/tag/InfoCircle.vue'),
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
