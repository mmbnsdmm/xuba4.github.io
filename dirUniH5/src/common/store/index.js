import Vue from 'vue'
import Vuex from 'vuex'
import Tool from '../tool'

Vue.use(Vuex);

let hasLogin = false;
let userInfo = {};
if (Tool.getCache('hasLogin')) {
    hasLogin = Tool.getCache('hasLogin');
    if (Tool.getCache('userInfo')) {
        userInfo = Tool.getCache('userInfo');
    }else{
        hasLogin = false
	}
}else{
    userInfo = {};
}

const store = new Vuex.Store({
	state: {
		hasLogin: hasLogin,
		userInfo: userInfo
	},
	mutations: {
		login(state, provider) {
			let userInfo = provider;
            if (!userInfo.avatar){
                userInfo.avatar = "/static/defaultavatar.jpg"
            }
			state.hasLogin = true;
			state.userInfo = userInfo;
			Tool.setCache('hasLogin', state.hasLogin);
			Tool.setCache('userInfo', state.userInfo);
		},
		logout(state) {
			state.hasLogin = false;
			state.userInfo = {};
            Tool.delCache('hasLogin');
            Tool.delCache('userInfo');
		}
	},
	actions: {},
    getters : {
        hasLogin: function (state) {
            return state.hasLogin;
        },
        userInfo: function (state) {
            return state.userInfo;
        }
    }
});

export default store
