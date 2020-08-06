// import modules from './modules'
import Vue from 'vue'
//这里仅示范npm安装方式的引入，其它方式引入请看最上面【安装】部分
import Router from 'uni-simple-router'
Vue.use(Router);

import Tool from '../tool'

import Store from '../store'

//初始化
const router = new Router({
    // routes: [...modules]//路由表
    routes: ROUTES
});

//全局路由前置守卫
router.beforeEach((to, from, next) => {
    if (Store.getters.hasLogin){
        if (to.authType === "?"){
            next('/pages/site/Home')
        }
    } else{
        if (to.authType === "@"){
            Tool.setCache('beforeLoginPath', to.path);
            next('/pages/site/Login')
        }
    }
    next()
});

// 全局路由后置守卫
router.afterEach((to, from) => {});

export default router