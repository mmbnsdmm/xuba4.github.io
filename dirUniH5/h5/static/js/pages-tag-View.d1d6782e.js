(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-tag-View"],{"0e5d":function(t,e,a){var n=a("4bad");e=n(!1),e.push([t.i,'@font-face{font-family:wi;\n    /** 阿里巴巴矢量图标库的字体库地址,你可以更换为你的图标库 **/src:url(http://at.alicdn.com/t/font_1840030_g5uzc9pbrun.ttf) format("truetype")}.wi[data-v-345dff48]{font-family:wi!important;font-size:%?34?%;padding:%?4?%}',""]),t.exports=e},"0f4d":function(t,e,a){"use strict";a.r(e);var n=a("24b2"),i=a("700a");for(var o in i)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return i[t]}))}(o);var r,c=a("f0c5"),d=Object(c["a"])(i["default"],n["b"],n["c"],!1,null,"0cc8b220",null,!1,n["a"],r);e["default"]=d.exports},2209:function(t,e,a){"use strict";var n;a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}));var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return t.text?a("v-uni-view",{staticClass:"uni-tag",class:["uni-tag--"+t.type,!0===t.disabled||"true"===t.disabled?"uni-tag--disabled":"",!0===t.inverted||"true"===t.inverted?t.type+"-uni-tag--inverted":"",!0===t.circle||"true"===t.circle?"uni-tag--circle":"",!0===t.mark||"true"===t.mark?"uni-tag--mark":"","uni-tag--"+t.size],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onClick()}}},[a("v-uni-text",{class:["default"===t.type?"uni-tag--default":"uni-tag-text",!0===t.inverted||"true"===t.inverted?"uni-tag-text--"+t.type:"","small"===t.size?"uni-tag-text--small":""]},[t._v(t._s(t.text))])],1):t._e()},o=[]},"24b2":function(t,e,a){"use strict";a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}));var n={uCard:a("d505").default,uIcon:a("f86b").default},i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"tag-view"},[a("ol",{staticClass:"breadcrumb"},[a("li",[a("v-uni-text",{staticClass:"text-blue",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.$router.push("/")}}},[t._v("首页")])],1),a("li",{staticClass:"active"},[t._v(t._s(t.tag.name))]),a("li",{staticClass:"active"},[t._v("圈内文章列表")]),t.tag.isYouJoin?t._e():a("v-uni-text",{staticClass:"text-blue pull-right",nativeOn:{click:function(e){return t.toJoin(e)}}},[t._v("加入")]),t.tag.isYouJoin?a("v-uni-text",{staticClass:"text-blue pull-right",nativeOn:{click:function(e){return t.toQuit(e)}}},[t._v("退出")]):t._e()],1),a("div",{staticClass:"row"},[a("div",{staticClass:"col-xs-12"},[a("WLoadMore",{ref:"WODROW_LOAD_MORE_ARTICLE_LIST",attrs:{pageSize:t.page_size,color:"#66ccff"},on:{provider:function(e){arguments[0]=e=t.$handleEvent(e),t.provider.apply(void 0,arguments)}},scopedSlots:t._u([{key:"list",fn:function(e){var n=e.items;return t._l(n,(function(e,n){return a("v-uni-view",{key:n,staticClass:"solid-top"},[a("u-card",{attrs:{padding:"10",margin:"15rpx",border:!1,"head-border-bottom":!1,"foot-border-top":!1,"title-size":"15rpx",title:e.createdBy.nickName,"sub-title":t.$moment(1e3*e.created_at).fromNow(),thumb:e.createdBy.avatar},on:{"body-click":function(a){arguments[0]=a=t.$handleEvent(a),t.toView(e.id,e.isUpdate)},"head-click":function(a){arguments[0]=a=t.$handleEvent(a),t.toAuthor(e.created_by)}}},[a("v-uni-view",{attrs:{slot:"body"},slot:"body"},[a("v-uni-view",[a("v-uni-text",{staticClass:"text-blue",staticStyle:{"font-size":"36rpx"},domProps:{innerHTML:t._s(e.title)}}),e.isUpdate?a("small",{staticClass:"pull-right text-danger"},[t._v("有更新")]):t._e()],1)],1),a("v-uni-view",{attrs:{slot:"foot"},slot:"foot"},[1===e.create_type?a("v-uni-text",{staticClass:"text-green"},[t._v(t._s(t.$conf.serverData.enums.article.createTypeDesc[e.create_type]))]):t._e(),2===e.create_type?a("v-uni-text",{staticClass:"text-danger"},[t._v(t._s(t.$conf.serverData.enums.article.createTypeDesc[e.create_type]))]):t._e(),3===e.create_type?a("v-uni-text",{staticClass:"text-warning"},[t._v(t._s(t.$conf.serverData.enums.article.createTypeDesc[e.create_type]))]):t._e(),e.created_by!==t.userInfo.id?a("WI",{staticClass:"single",attrs:{type:"&#xe62b;","font-size":"34rpx"}}):t._e(),e.created_by===t.userInfo.id||e.isYouCollection?t._e():a("u-icon",{attrs:{name:"star",label:e.collectionTotal},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.collect(n)}}}),e.created_by!==t.userInfo.id&&e.isYouCollection?a("u-icon",{attrs:{name:"star-fill",label:e.collectionTotal},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.unCollect(n)}}}):t._e(),e.get_password&&!e.canView?a("u-icon",{attrs:{name:"lock-fill"}}):t._e(),e.get_password&&e.canView?a("u-icon",{attrs:{name:"lock-open"}}):t._e(),a("u-icon",{staticClass:"pull-right text-blue",attrs:{name:"eye-fill",size:"34",color:"",label:"查看"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.toView(e.id,e.isUpdate)}}}),e.canYouOpt?a("u-icon",{staticClass:"pull-right text-warning",attrs:{name:"edit-pen-fill",size:"34",color:"",label:"修改"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.toUpdate(e.id,e.isUpdate)}}}):t._e(),e.canYouOpt?a("u-icon",{staticClass:"pull-right text-danger",attrs:{name:"close",size:"34",color:"",label:"删除"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.toDelete(n)}}}):t._e(),a("div",{staticClass:"clearfix"})],1)],1)],1)}))}}])})],1)]),a("ScrollTopIcon",{on:{tapIcon:function(e){arguments[0]=e=t.$handleEvent(e),t.tapIcon.apply(void 0,arguments)}}})],1)},o=[]},"31aa":function(t,e,a){"use strict";a.r(e);var n=a("ad59"),i=a.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},"67d9":function(t,e,a){"use strict";var n;a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}));var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-text",{staticClass:"wi",style:{color:t.color,fontSize:t.fontSize},domProps:{innerHTML:t._s(t.type)}})},o=[]},"700a":function(t,e,a){"use strict";a.r(e);var n=a("9ce0"),i=a.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},"707e":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"WI",props:{type:{type:String,default:"&#xe659;"},color:{type:String,default:"#666666"},fontSize:{type:String,default:"34rpx"}},methods:{}};e.default=n},7835:function(t,e,a){var n=a("0e5d");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("52ef2f88",n,!0,{sourceMap:!1,shadowMode:!1})},"7fcc":function(t,e,a){"use strict";a.r(e);var n=a("707e"),i=a.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},"889f":function(t,e,a){"use strict";var n=a("7835"),i=a.n(n);i.a},9270:function(t,e,a){var n=a("9f70");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("3a310e88",n,!0,{sourceMap:!1,shadowMode:!1})},"9ce0":function(t,e,a){"use strict";var n=a("4ea4");a("4160"),a("159b"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n(a("5530")),o=n(a("edc3")),r=n(a("03a9")),c=n(a("d719")),d=n(a("fbda")),u=a("2f62"),l=a("b970"),s={name:"TagView",components:{WLoadMore:o.default,ScrollTopIcon:r.default,uniTag:c.default,WI:d.default},computed:(0,i.default)({},(0,u.mapState)(["userInfo"])),data:function(){return{tagId:"",tag:{},page:0,page_size:10,total:0}},onLoad:function(t){var e=this;t.id||((0,l.Toast)("参数id传递异常"),uni.navigateBack()),e.tagId=t.id},mounted:function(){var t=this;t.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.reLoadData(),t.getTagInfo()},methods:{provider:function(t){var e=this;setTimeout((function(){var a=e.getData(t);e.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.pushData(a)}),1e3)},getData:function(t){var e=this,a=[],n={page:t.pageNo,page_size:t.pageSize,json_filter_params:JSON.stringify(["=","status",10])};return e.tagId&&(n.tagId=e.tagId),e.$auth.post("/article/default/list",n,!1,(function(t){e.$_.forEach(t.list,(function(t,n){t.isUpdate=!1;var i=e.$models.Article.getIsSetById(t.id);if(i&&i.updated_at<t.updated_at&&(t.isUpdate=!0),t.canYouOpt)t.canView=!0;else if(t.get_password){var o=e.$auth.getSession("article-get-password-"+t.id);t.canView=o===t.get_password}else t.canView=!1;a.push(t)}))}),(function(t){(0,l.Toast)(t)})),a},getTagInfo:function(){var t=this;t.$auth.post("/tag/default/view",{id:t.tagId},!0,(function(e){t.tag=e.tag}),(function(t){(0,l.Toast)(t)}))},toJoin:function(){var t=this;l.Dialog.confirm({title:"确认加入",message:"你确认要加入此圈子吗？"}).then((function(){var e={id:t.tagId};t.$auth.post("/tag/default/join",e,!0,(function(e){t.$set(t.$data,"tag",e.tag)}),(function(t){(0,l.Toast)(t)}))})).catch((function(){}))},toQuit:function(){var t=this;l.Dialog.confirm({title:"确认退出",message:"你确认要退出此圈子吗？"}).then((function(){var e={id:t.tagId};t.$auth.post("/tag/default/quit",e,!0,(function(e){t.$set(t.$data,"tag",e.tag)}),(function(t){(0,l.Toast)(t)}))})).catch((function(){}))},tapIcon:function(t){uni.pageScrollTo({duration:60,scrollTop:0})},toView:function(t,e){uni.navigateTo({url:"/pages/article/View?id="+t+"&isLast="+e})},toUpdate:function(t,e){uni.navigateTo({url:"/pages/article/Update?id="+t+"&isLast="+e})},toDelete:function(t){var e=this;l.Dialog.confirm({title:"确认删除",message:"你确认要删除此条记录吗？"}).then((function(){var a={id:e.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.getItem(t).id};e.$auth.post("/article/default/delete",a,!0,(function(t){e.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.reLoadData()}),(function(t){(0,l.Toast)(t)}))})).catch((function(){}))},toAuthor:function(t){uni.navigateTo({url:"/pages/user/profile/Index?id="+t})},toCircle:function(t){uni.navigateTo({url:"/pages/tag/View?id="+t})},collect:function(t){var e=this,a={id:e.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.getItem(t).id};e.$auth.post("/article/default/collection",a,!0,(function(a){e.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.updateItem(t,a.info)}),(function(t){(0,l.Toast)(t)}))},unCollect:function(t){var e=this,a={id:e.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.getItem(t).id};e.$auth.post("/article/default/un-collection",a,!0,(function(a){e.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.updateItem(t,a.info)}),(function(t){(0,l.Toast)(t)}))}},onReady:function(){this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.reLoadData()},onPullDownRefresh:function(){this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.pullDownRefresh()},onReachBottom:function(){this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.reachBottom()}};e.default=s},"9f70":function(t,e,a){var n=a("4bad");e=n(!1),e.push([t.i,".uni-tag[data-v-ec04006a]{\ndisplay:-webkit-box;display:-webkit-flex;display:flex;\npadding:0 16px;height:30px;line-height:30px;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;color:#333;border-radius:%?6?%;background-color:#f8f8f8;border-width:%?1?%;border-style:solid;border-color:#f8f8f8}.uni-tag--circle[data-v-ec04006a]{border-radius:15px}.uni-tag--mark[data-v-ec04006a]{border-top-left-radius:0;border-bottom-left-radius:0;border-top-right-radius:15px;border-bottom-right-radius:15px}.uni-tag--disabled[data-v-ec04006a]{opacity:.5}.uni-tag--small[data-v-ec04006a]{height:20px;padding:0 8px;line-height:20px;font-size:%?24?%}.uni-tag--default[data-v-ec04006a]{color:#333;font-size:%?28?%}.uni-tag-text--small[data-v-ec04006a]{font-size:%?24?%!important}.uni-tag-text[data-v-ec04006a]{color:#fff;font-size:%?28?%}.uni-tag-text--primary[data-v-ec04006a]{color:#007aff!important}.uni-tag-text--success[data-v-ec04006a]{color:#4cd964!important}.uni-tag-text--warning[data-v-ec04006a]{color:#f0ad4e!important}.uni-tag-text--error[data-v-ec04006a]{color:#dd524d!important}.uni-tag--primary[data-v-ec04006a]{color:#fff;background-color:#007aff;border-width:%?1?%;border-style:solid;border-color:#007aff}.primary-uni-tag--inverted[data-v-ec04006a]{color:#007aff;background-color:#fff;border-width:%?1?%;border-style:solid;border-color:#007aff}.uni-tag--success[data-v-ec04006a]{color:#fff;background-color:#4cd964;border-width:%?1?%;border-style:solid;border-color:#4cd964}.success-uni-tag--inverted[data-v-ec04006a]{color:#4cd964;background-color:#fff;border-width:%?1?%;border-style:solid;border-color:#4cd964}.uni-tag--warning[data-v-ec04006a]{color:#fff;background-color:#f0ad4e;border-width:%?1?%;border-style:solid;border-color:#f0ad4e}.warning-uni-tag--inverted[data-v-ec04006a]{color:#f0ad4e;background-color:#fff;border-width:%?1?%;border-style:solid;border-color:#f0ad4e}.uni-tag--error[data-v-ec04006a]{color:#fff;background-color:#dd524d;border-width:%?1?%;border-style:solid;border-color:#dd524d}.error-uni-tag--inverted[data-v-ec04006a]{color:#dd524d;background-color:#fff;border-width:%?1?%;border-style:solid;border-color:#dd524d}.uni-tag--inverted[data-v-ec04006a]{color:#333;background-color:#fff;border-width:%?1?%;border-style:solid;border-color:#f8f8f8}",""]),t.exports=e},ad59:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"UniTag",props:{type:{type:String,default:"default"},size:{type:String,default:"normal"},text:{type:String,default:""},disabled:{type:[Boolean,String],default:!1},inverted:{type:[Boolean,String],default:!1},circle:{type:[Boolean,String],default:!1},mark:{type:[Boolean,String],default:!1}},methods:{onClick:function(){!0!==this.disabled&&"true"!==this.disabled&&this.$emit("click")}}};e.default=n},d719:function(t,e,a){"use strict";a.r(e);var n=a("2209"),i=a("31aa");for(var o in i)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("eaa0");var r,c=a("f0c5"),d=Object(c["a"])(i["default"],n["b"],n["c"],!1,null,"ec04006a",null,!1,n["a"],r);e["default"]=d.exports},eaa0:function(t,e,a){"use strict";var n=a("9270"),i=a.n(n);i.a},fbda:function(t,e,a){"use strict";a.r(e);var n=a("67d9"),i=a("7fcc");for(var o in i)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("889f");var r,c=a("f0c5"),d=Object(c["a"])(i["default"],n["b"],n["c"],!1,null,"345dff48",null,!1,n["a"],r);e["default"]=d.exports}}]);