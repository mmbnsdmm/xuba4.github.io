(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-center-MyAttention"],{"0e5d":function(t,e,a){var r=a("4bad");e=r(!1),e.push([t.i,'@font-face{font-family:wi;\n    /** 阿里巴巴矢量图标库的字体库地址,你可以更换为你的图标库 **/src:url(http://at.alicdn.com/t/font_1840030_g5uzc9pbrun.ttf) format("truetype")}.wi[data-v-345dff48]{font-family:wi!important;font-size:%?34?%;padding:%?4?%}',""]),t.exports=e},"19c7":function(t,e,a){"use strict";a.r(e);var r=a("e568"),o=a("2cb5");for(var n in o)["default"].indexOf(n)<0&&function(t){a.d(e,t,(function(){return o[t]}))}(n);var i,d=a("f0c5"),c=Object(d["a"])(o["default"],r["b"],r["c"],!1,null,"5aff6506",null,!1,r["a"],i);e["default"]=c.exports},2209:function(t,e,a){"use strict";var r;a.d(e,"b",(function(){return o})),a.d(e,"c",(function(){return n})),a.d(e,"a",(function(){return r}));var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return t.text?a("v-uni-view",{staticClass:"uni-tag",class:["uni-tag--"+t.type,!0===t.disabled||"true"===t.disabled?"uni-tag--disabled":"",!0===t.inverted||"true"===t.inverted?t.type+"-uni-tag--inverted":"",!0===t.circle||"true"===t.circle?"uni-tag--circle":"",!0===t.mark||"true"===t.mark?"uni-tag--mark":"","uni-tag--"+t.size],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onClick()}}},[a("v-uni-text",{class:["default"===t.type?"uni-tag--default":"uni-tag-text",!0===t.inverted||"true"===t.inverted?"uni-tag-text--"+t.type:"","small"===t.size?"uni-tag-text--small":""]},[t._v(t._s(t.text))])],1):t._e()},n=[]},"2cb5":function(t,e,a){"use strict";a.r(e);var r=a("6a00"),o=a.n(r);for(var n in r)["default"].indexOf(n)<0&&function(t){a.d(e,t,(function(){return r[t]}))}(n);e["default"]=o.a},"31aa":function(t,e,a){"use strict";a.r(e);var r=a("ad59"),o=a.n(r);for(var n in r)["default"].indexOf(n)<0&&function(t){a.d(e,t,(function(){return r[t]}))}(n);e["default"]=o.a},"67d9":function(t,e,a){"use strict";var r;a.d(e,"b",(function(){return o})),a.d(e,"c",(function(){return n})),a.d(e,"a",(function(){return r}));var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-text",{staticClass:"wi",style:{color:t.color,fontSize:t.fontSize},domProps:{innerHTML:t._s(t.type)}})},n=[]},"6a00":function(t,e,a){"use strict";var r=a("4ea4");a("4160"),a("159b"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=r(a("5530")),n=r(a("edc3")),i=r(a("03a9")),d=r(a("d719")),c=r(a("fbda")),u=a("b970"),f=a("2f62"),l={name:"UserCenterMyAttention",components:{WLoadMore:n.default,ScrollTopIcon:i.default,uniTag:d.default,WI:c.default},computed:(0,o.default)({},(0,f.mapState)(["userInfo"])),data:function(){return{page:0,page_size:10,total:0}},mounted:function(){this.$refs.WODROW_LOAD_MORE_MY_ATTENTIONS.reLoadData()},methods:{provider:function(t){var e=this;setTimeout((function(){var a=e.getData(t);e.$refs.WODROW_LOAD_MORE_MY_ATTENTIONS.pushData(a)}),1e3)},getData:function(t){var e=this,a=[],r={page:t.pageNo,page_size:t.pageSize,attentionsForUserId:e.userInfo.id};return e.$auth.post("/user/profile/list",r,!1,(function(t){e.$_.forEach(t.list,(function(t,e){a.push(t)}))}),(function(t){(0,u.Toast)(t)})),a},tapIcon:function(t){uni.pageScrollTo({duration:60,scrollTop:0})},toUnAttention:function(t){var e=this;u.Dialog.confirm({title:"确认取消关注",message:"你确认要确认取消关注此用户吗？"}).then((function(){var a={id:e.$refs.WODROW_LOAD_MORE_MY_ATTENTIONS.getItem(t).id};e.$auth.post("/user/profile/un-attention",a,!0,(function(t){e.$refs.WODROW_LOAD_MORE_MY_ATTENTIONS.reLoadData()}),(function(t){(0,u.Toast)(t)}))})).catch((function(){}))},toAuthor:function(t){uni.navigateTo({url:"/pages/user/profile/Index?id="+t})}},onReady:function(){this.$refs.WODROW_LOAD_MORE_MY_ATTENTIONS.reLoadData()},onPullDownRefresh:function(){this.$refs.WODROW_LOAD_MORE_MY_ATTENTIONS.pullDownRefresh()},onReachBottom:function(){this.$refs.WODROW_LOAD_MORE_MY_ATTENTIONS.reachBottom()}};e.default=l},"707e":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r={name:"WI",props:{type:{type:String,default:"&#xe659;"},color:{type:String,default:"#666666"},fontSize:{type:String,default:"34rpx"}},methods:{}};e.default=r},7835:function(t,e,a){var r=a("0e5d");"string"===typeof r&&(r=[[t.i,r,""]]),r.locals&&(t.exports=r.locals);var o=a("4f06").default;o("52ef2f88",r,!0,{sourceMap:!1,shadowMode:!1})},"7fcc":function(t,e,a){"use strict";a.r(e);var r=a("707e"),o=a.n(r);for(var n in r)["default"].indexOf(n)<0&&function(t){a.d(e,t,(function(){return r[t]}))}(n);e["default"]=o.a},"889f":function(t,e,a){"use strict";var r=a("7835"),o=a.n(r);o.a},9270:function(t,e,a){var r=a("9f70");"string"===typeof r&&(r=[[t.i,r,""]]),r.locals&&(t.exports=r.locals);var o=a("4f06").default;o("3a310e88",r,!0,{sourceMap:!1,shadowMode:!1})},"9f70":function(t,e,a){var r=a("4bad");e=r(!1),e.push([t.i,".uni-tag[data-v-ec04006a]{\ndisplay:-webkit-box;display:-webkit-flex;display:flex;\npadding:0 16px;height:30px;line-height:30px;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;color:#333;border-radius:%?6?%;background-color:#f8f8f8;border-width:%?1?%;border-style:solid;border-color:#f8f8f8}.uni-tag--circle[data-v-ec04006a]{border-radius:15px}.uni-tag--mark[data-v-ec04006a]{border-top-left-radius:0;border-bottom-left-radius:0;border-top-right-radius:15px;border-bottom-right-radius:15px}.uni-tag--disabled[data-v-ec04006a]{opacity:.5}.uni-tag--small[data-v-ec04006a]{height:20px;padding:0 8px;line-height:20px;font-size:%?24?%}.uni-tag--default[data-v-ec04006a]{color:#333;font-size:%?28?%}.uni-tag-text--small[data-v-ec04006a]{font-size:%?24?%!important}.uni-tag-text[data-v-ec04006a]{color:#fff;font-size:%?28?%}.uni-tag-text--primary[data-v-ec04006a]{color:#007aff!important}.uni-tag-text--success[data-v-ec04006a]{color:#4cd964!important}.uni-tag-text--warning[data-v-ec04006a]{color:#f0ad4e!important}.uni-tag-text--error[data-v-ec04006a]{color:#dd524d!important}.uni-tag--primary[data-v-ec04006a]{color:#fff;background-color:#007aff;border-width:%?1?%;border-style:solid;border-color:#007aff}.primary-uni-tag--inverted[data-v-ec04006a]{color:#007aff;background-color:#fff;border-width:%?1?%;border-style:solid;border-color:#007aff}.uni-tag--success[data-v-ec04006a]{color:#fff;background-color:#4cd964;border-width:%?1?%;border-style:solid;border-color:#4cd964}.success-uni-tag--inverted[data-v-ec04006a]{color:#4cd964;background-color:#fff;border-width:%?1?%;border-style:solid;border-color:#4cd964}.uni-tag--warning[data-v-ec04006a]{color:#fff;background-color:#f0ad4e;border-width:%?1?%;border-style:solid;border-color:#f0ad4e}.warning-uni-tag--inverted[data-v-ec04006a]{color:#f0ad4e;background-color:#fff;border-width:%?1?%;border-style:solid;border-color:#f0ad4e}.uni-tag--error[data-v-ec04006a]{color:#fff;background-color:#dd524d;border-width:%?1?%;border-style:solid;border-color:#dd524d}.error-uni-tag--inverted[data-v-ec04006a]{color:#dd524d;background-color:#fff;border-width:%?1?%;border-style:solid;border-color:#dd524d}.uni-tag--inverted[data-v-ec04006a]{color:#333;background-color:#fff;border-width:%?1?%;border-style:solid;border-color:#f8f8f8}",""]),t.exports=e},ad59:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r={name:"UniTag",props:{type:{type:String,default:"default"},size:{type:String,default:"normal"},text:{type:String,default:""},disabled:{type:[Boolean,String],default:!1},inverted:{type:[Boolean,String],default:!1},circle:{type:[Boolean,String],default:!1},mark:{type:[Boolean,String],default:!1}},methods:{onClick:function(){!0!==this.disabled&&"true"!==this.disabled&&this.$emit("click")}}};e.default=r},d719:function(t,e,a){"use strict";a.r(e);var r=a("2209"),o=a("31aa");for(var n in o)["default"].indexOf(n)<0&&function(t){a.d(e,t,(function(){return o[t]}))}(n);a("eaa0");var i,d=a("f0c5"),c=Object(d["a"])(o["default"],r["b"],r["c"],!1,null,"ec04006a",null,!1,r["a"],i);e["default"]=c.exports},e568:function(t,e,a){"use strict";a.d(e,"b",(function(){return o})),a.d(e,"c",(function(){return n})),a.d(e,"a",(function(){return r}));var r={uCard:a("d505").default,uIcon:a("f86b").default},o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"user-center-my-attention"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-xs-12"},[a("WLoadMore",{ref:"WODROW_LOAD_MORE_MY_ATTENTIONS",attrs:{pageSize:t.page_size,color:"#66ccff"},on:{provider:function(e){arguments[0]=e=t.$handleEvent(e),t.provider.apply(void 0,arguments)}},scopedSlots:t._u([{key:"list",fn:function(e){var r=e.items;return t._l(r,(function(e,r){return a("v-uni-view",{key:r,staticClass:"solid-top"},[a("u-card",{attrs:{padding:"10",margin:"15rpx","show-foot":!1,border:!1,"head-border-bottom":!1,"foot-border-top":!1,"title-size":"15rpx",title:e.nickName,thumb:e.avatar},on:{"head-click":function(a){arguments[0]=a=t.$handleEvent(a),t.toAuthor(e.id)}}},[a("v-uni-view",{attrs:{slot:"body"},slot:"body"},[a("v-uni-text",[t._v("关注:"),a("code",[t._v(t._s(e.attentionTotal))])]),a("v-uni-text",[t._v("粉丝:"),a("code",[t._v(t._s(e.fansTotal))])]),a("v-uni-text",[t._v("文章:"),a("code",[t._v(t._s(e.articleTotal))])]),a("v-uni-text",[t._v("收藏:"),a("code",[t._v(t._s(e.collectionTotal))])]),a("u-icon",{staticClass:"pull-right text-danger",attrs:{name:"close",size:"34",color:"",label:"取消关注"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.toUnAttention(r)}}}),a("div",{staticClass:"clearfix"})],1)],1)],1)}))}}])})],1)]),a("ScrollTopIcon",{on:{tapIcon:function(e){arguments[0]=e=t.$handleEvent(e),t.tapIcon.apply(void 0,arguments)}}})],1)},n=[]},eaa0:function(t,e,a){"use strict";var r=a("9270"),o=a.n(r);o.a},fbda:function(t,e,a){"use strict";a.r(e);var r=a("67d9"),o=a("7fcc");for(var n in o)["default"].indexOf(n)<0&&function(t){a.d(e,t,(function(){return o[t]}))}(n);a("889f");var i,d=a("f0c5"),c=Object(d["a"])(o["default"],r["b"],r["c"],!1,null,"345dff48",null,!1,r["a"],i);e["default"]=c.exports}}]);