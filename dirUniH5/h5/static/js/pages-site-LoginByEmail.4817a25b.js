(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-site-LoginByEmail"],{"0f6b":function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return s})),i.d(e,"a",(function(){return a}));var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"site-login-by-email"},[i("v-uni-view",{staticClass:"container"},[i("div",{staticClass:"row"},[i("div",{staticClass:"col-xs-12"},[i("h4",[t._v("邮箱登录")]),i("div",{staticClass:"form-group"},[i("v-uni-label",[t._v("邮箱")]),i("v-uni-input",{staticClass:"form-control",attrs:{type:"email",placeholder:"请输入邮箱",required:!0},model:{value:t.email,callback:function(e){t.email=e},expression:"email"}})],1),i("div",{staticClass:"row"},[i("div",{staticClass:"col-xs-7"},[i("div",{staticClass:"form-group"},[i("v-uni-input",{staticClass:"form-control",attrs:{type:"text",placeholder:"请输入验证码",required:!0},model:{value:t.emailVerifyCode,callback:function(e){t.emailVerifyCode=e},expression:"emailVerifyCode"}})],1)]),i("div",{staticClass:"col-xs-5"},[i("v-uni-button",{directives:[{name:"preventReClick",rawName:"v-preventReClick"}],staticClass:"btn btn-warning btn-block",attrs:{disabled:t.isBtnSendVerifyCodedisabled},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.sendVerifyCode.apply(void 0,arguments)}}},[t._v("发送验证码"+t._s(t.countDownSendCode))])],1)]),i("div",{staticClass:"help-block"},[i("v-uni-navigator",{staticClass:"pull-left",attrs:{url:"/pages/site/Login"}},[i("v-uni-text",{staticClass:"text-blue"},[t._v("用户名登陆")])],1),i("v-uni-text",{staticClass:"pull-left",attrs:{decode:!1}},[t._v("|")]),i("v-uni-navigator",{staticClass:"pull-left",attrs:{url:"/pages/site/Signup"}},[i("v-uni-text",{staticClass:"text-blue"},[t._v("注册")])],1),i("v-uni-navigator",{staticClass:"pull-right",attrs:{url:"/pages/site/ResetPassword"}},[i("v-uni-text",{staticClass:"text-blue"},[t._v("忘记密码")])],1),i("v-uni-text",{staticClass:"pull-right",attrs:{decode:!1}},[t._v("|")]),i("v-uni-text",{staticClass:"text-blue pull-right",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.$router.push("/")}}},[t._v("首页")]),i("div",{staticClass:"clearfix"})],1),i("v-uni-button",{staticClass:"btn btn-primary btn-block btn-lg",attrs:{disabled:t.isBtnDisabled},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.toLoginByEmail.apply(void 0,arguments)}}},[t._v("登录")])],1)])])],1)},s=[]},"339f":function(t,e,i){"use strict";i.r(e);var a=i("0f6b"),n=i("6d3e");for(var s in n)"default"!==s&&function(t){i.d(e,t,(function(){return n[t]}))}(s);var o,l=i("f0c5"),r=Object(l["a"])(n["default"],a["b"],a["c"],!1,null,"42f13efc",null,!1,a["a"],o);e["default"]=r.exports},"50fb":function(t,e,i){"use strict";var a=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=a(i("5530")),s=i("b970"),o=i("2f62"),l={name:"SiteLoginByEmail",data:function(){return{email:"",emailVerifyCode:"",countDownSendCode:"",isBtnSendVerifyCodedisabled:!1,isBtnDisabled:!1}},computed:(0,n.default)({},(0,o.mapState)(["hasLogin"])),mounted:function(){},methods:(0,n.default)((0,n.default)({},(0,o.mapMutations)(["login"])),{},{sendVerifyCode:function(){var t=this;/^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test(t.email)?t.$http.post("/site/send-email-code",{email:this.email,typeKey:2},!0,(function(e){(0,s.Toast)(e.msg),t.countDownSendCode=120,t.isBtnSendVerifyCodedisabled=!0;var i=setInterval((function(){t.countDownSendCode--,t.countDownSendCode<=0&&(t.countDownSendCode="",t.isBtnSendVerifyCodedisabled=!1,clearInterval(i))}),1e3)}),(function(e){(0,s.Toast)(e),t.isBtnDisabled=!1})):(0,s.Toast)("邮箱格式不正确")},toLoginByEmail:function(){var t=this;/^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test(t.email)?t.emailVerifyCode?(t.isBtnDisabled=!0,t.$http.post("/site/login-by-email",{email:t.email,code:t.emailVerifyCode},!0,(function(e){(0,s.Toast)(e.msg),t.login(e.user),t.hasLogin?t.$tool.getCache("beforeLoginPath")?t.$router.push(t.$tool.getCache("beforeLoginPath")):t.$router.go(-1):((0,s.Toast)("登陆失败，请联系管理员"),t.isBtnDisabled=!1)}),(function(e){(0,s.Toast)(e),t.isBtnDisabled=!1}))):(0,s.Toast)("验证码不能为空"):(0,s.Toast)("邮箱格式不正确")}})};e.default=l},"6d3e":function(t,e,i){"use strict";i.r(e);var a=i("50fb"),n=i.n(a);for(var s in a)"default"!==s&&function(t){i.d(e,t,(function(){return a[t]}))}(s);e["default"]=n.a}}]);