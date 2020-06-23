import Vue from 'vue'
import Vuex from 'vuex'
import Axios from './axios'
import Tool from './tool'

function _resetUser(){
    return {
        id: 0,
        token: "",
        key: "",
        username: "",
        email: "",
        amount: 0.00,
        frozen: 0.00,
        availableAmount: 0.00,
        deposit: 0.00,
        level: 0,
        integral: 0,
        uclass: 1,
        alipay_income_image: "",
        weixin_income_image: ""
    };
}

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        isAuth: Tool.getStorage('isAuth') || false,
        isLgRumen: Tool.getStorage('isLgRumen') || false,
        user: Tool.getStorage('user') || _resetUser()
    },
    mutations: {
        authSuccess: function(state, user){
            Tool.setStorage('isAuth', true);
            Tool.setStorage('user', user);
            if (user.uclass > 1){
                Tool.setStorage('isLgRumen', true);
                state.isLgRumen = true;
            }
            state.isAuth = true;
            state.user = user;
        },
        logout: function(state){
            Tool.setStorage('isAuth', false);
            Tool.setStorage('isLgRumen', false);
            Tool.deleteStorage('user');
            state.isAuth = false;
            state.isLgRumen = false;
            state.user = _resetUser();
        }
    },
    actions: {
        login: function({commit}, user){
            return new Promise((resolve, reject) => {
                Axios.post('/site/login', user).then(resp => {
                    if (resp.data.data.status !== 200){
                        commit('logout');
                        resolve(resp);
                    }else{
                        commit('authSuccess', resp.data.data.user);
                        resolve(resp);
                    }
                }).catch(err => {
                    reject(err)
                });
            });
        },
        loginByEmail: function({commit}, data){
            return new Promise((resolve, reject) => {
                Axios.post('/site/login-by-email', data).then(resp => {
                    if (resp.data.data.status !== 200){
                        commit('logout');
                        resolve(resp);
                    }else{
                        commit('authSuccess', resp.data.data.user);
                        resolve(resp);
                    }
                }).catch(err => {
                    reject(err)
                });
            });
        },
        signup: function({commit}, user){
            return new Promise((resolve, reject) => {
                Axios.post('/site/signup', user).then(resp => {
                    if (resp.data.data.status !== 200){
                        commit('logout');
                        resolve(resp);
                    }else{
                        commit('logout');
                        resolve(resp);
                    }
                }).catch(err => {
                    reject(err);
                });
            })
        },
        updateUser: function({commit}, formParams){
            return new Promise((resolve, reject) => {
                Axios.post('/user/center/info', formParams).then(resp => {
                    if (resp.data.code !== 200){
                        resolve(resp);
                    }else{
                        commit('authSuccess', resp.data.data.user);
                        resolve(resp);
                    }
                }).catch(err => {
                    reject(err)
                });
            });
        },
        logout: function({commit}){
            return new Promise((resolve) => {
                commit('logout');
                resolve();
            })
        }
    },
    getters : {
        isAuth: function (state) {
            // Tool.log(Tool.getStorage('isAuth'));
            // Tool.log(Tool.getStorage('user'));
            // return Tool.getStorage('isAuth');
            return state.isAuth;
        },
        user: function (state) {
            return state.user;
        },
        isLgRumen: function (state) {
            return state.isLgRumen;
        }
    },
    modules: {}
})
