(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-public-login"],{"00e7":function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"container"},[i("v-uni-view",{staticClass:"left-bottom-sign"}),i("v-uni-view",{staticClass:"back-btn yticon icon-zuojiantou-up",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.navBack.apply(void 0,arguments)}}}),i("v-uni-view",{staticClass:"right-top-sign"}),i("v-uni-view",{staticClass:"wrapper"},[i("v-uni-view",{staticClass:"left-top-sign"},[t._v("LOGIN")]),i("v-uni-view",{staticClass:"welcome"},[t._v("欢迎回来！")]),i("v-uni-view",{staticClass:"input-content"},[i("v-uni-view",{staticClass:"login-way"},[i("v-uni-view",{staticClass:"left nav",class:{active:!t.loginWay},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.setLoginWay(0)}}},[t._v("账号密码登录")]),i("v-uni-view",{staticClass:"right nav",class:{active:t.loginWay},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.setLoginWay(1)}}},[t._v("短信验证码登录")])],1),0==t.loginWay?i("v-uni-view",{staticClass:"input-item"},[i("v-uni-text",{staticClass:"tit"},[t._v("账户")]),i("v-uni-input",{attrs:{type:"text",placeholder:"请输入用户名/手机号码/邮箱",maxlength:"11","data-key":"input"},model:{value:t.username,callback:function(e){t.username=e},expression:"username"}})],1):t._e(),0==t.loginWay?i("v-uni-view",{staticClass:"input-item"},[i("v-uni-text",{staticClass:"tit"},[t._v("密码")]),i("v-uni-input",{attrs:{type:"mobile",placeholder:"8-18位不含特殊字符的数字、字母组合","placeholder-class":"input-empty",maxlength:"20",password:"","data-key":"password"},on:{confirm:function(e){arguments[0]=e=t.$handleEvent(e),t.toLogin.apply(void 0,arguments)}},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}})],1):t._e(),1==t.loginWay?i("v-uni-view",{staticClass:"input-item"},[i("v-uni-text",{staticClass:"tit"},[t._v("手机号")]),i("v-uni-input",{attrs:{type:"text",placeholder:"请输入手机号码",maxlength:"11","data-key":"mobile"},model:{value:t.mobile,callback:function(e){t.mobile=e},expression:"mobile"}})],1):t._e(),1==t.loginWay?i("v-uni-view",{staticClass:"input-item"},[i("v-uni-view",{staticClass:"verify-code-selection"},[i("v-uni-text",{staticClass:"tit"},[t._v("验证码")]),i("v-uni-text",{staticClass:"btn-get-code",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.getCode.apply(void 0,arguments)}}},[t._v("获取验证码")])],1),i("v-uni-input",{attrs:{type:"number",value:"",placeholder:"请输入验证码","placeholder-class":"input-empty",maxlength:"8",password:"","data-key":"code"},on:{input:function(e){arguments[0]=e=t.$handleEvent(e),t.inputChange.apply(void 0,arguments)},confirm:function(e){arguments[0]=e=t.$handleEvent(e),t.toLogin.apply(void 0,arguments)}}})],1):t._e()],1),i("v-uni-button",{staticClass:"confirm-btn",attrs:{disabled:t.logining},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.toLogin.apply(void 0,arguments)}}},[t._v("登录")])],1)],1)},a=[];i.d(e,"a",function(){return n}),i.d(e,"b",function(){return a})},"0b56":function(t,e,i){"use strict";var n=i("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,i("96cf");var a=n(i("3b8d")),o=n(i("cebc")),s=i("2f62"),r={data:function(){return{username:"",password:"",logining:!1,referer:null,loginWay:0,mobile:"",code:""}},onLoad:function(t){this.referer=t.referer?t.referer:null},methods:(0,o.default)({},(0,s.mapActions)(["login","getCartProducts"]),{inputChange:function(t){var e=t.currentTarget.dataset.key;this[e]=t.detail.value},setLoginWay:function(t){this.loginWay=t},getCode:function(){uni.showLoading({title:"短信发送中"}),this.$http.post("index.code.get",{mobile:this.mobile}).then(function(t){uni.hideLoading(),uni.showToast({title:"短信已发送"})})},navBack:function(){uni.navigateBack()},toRegist:function(){uni.navigateTo({url:"/pages/public/registe"})},toCenter:function(){uni.switchTab({url:"/pages/user/user"})},toLogin:function(){var t=(0,a.default)(regeneratorRuntime.mark(function t(){var e,i,n,a,o,s=this;return regeneratorRuntime.wrap(function(t){while(1)switch(t.prev=t.next){case 0:this.logining=!0,e={},0==this.loginWay?(i=this.username,n=this.password,e={username:i,password:n,loginWay:this.loginWay}):(a=this.mobile,o=this.code,e={mobile:a,code:o,loginWay:this.loginWay}),this.login(e).then(function(t){s.logining=!1,s.getCartProducts(),s.referer?uni.navigateTo({url:decodeURIComponent(s.referer),fail:s.toCenter()}):s.toCenter()}).catch(function(t){s.logining=!1});case 4:case"end":return t.stop()}},t,this)}));function e(){return t.apply(this,arguments)}return e}()})};e.default=r},1804:function(t,e,i){"use strict";i.r(e);var n=i("00e7"),a=i("f1f8");for(var o in a)"default"!==o&&function(t){i.d(e,t,function(){return a[t]})}(o);i("f6e5");var s=i("2877"),r=Object(s["a"])(a["default"],n["a"],n["b"],!1,null,"3de02bc8",null);e["default"]=r.exports},"185e":function(t,e,i){var n=i("d973");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("5c2b831c",n,!0,{sourceMap:!1,shadowMode:!1})},d973:function(t,e,i){e=t.exports=i("2350")(!1),e.push([t.i,'@charset "UTF-8";\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */uni-page-body[data-v-3de02bc8]{background:#fff}.container[data-v-3de02bc8]{position:relative;width:100vw;height:100vh;overflow:hidden;background:#fff}.wrapper[data-v-3de02bc8]{position:relative;z-index:90;background:#fff;padding-bottom:%?40?%}.back-btn[data-v-3de02bc8]{position:absolute;left:%?40?%;z-index:9999;padding-top:0;top:%?40?%;font-size:%?40?%;color:#303133}.left-top-sign[data-v-3de02bc8]{font-size:%?120?%;color:#f8f8f8;position:relative;left:%?-16?%}.right-top-sign[data-v-3de02bc8]{position:absolute;top:%?80?%;right:%?-30?%;z-index:95}.right-top-sign[data-v-3de02bc8]:after,.right-top-sign[data-v-3de02bc8]:before{display:block;content:"";width:%?400?%;height:%?80?%;background:#b4f3e2}.right-top-sign[data-v-3de02bc8]:before{-webkit-transform:rotate(50deg);transform:rotate(50deg);border-radius:0 50px 0 0}.right-top-sign[data-v-3de02bc8]:after{position:absolute;right:%?-198?%;top:0;-webkit-transform:rotate(-50deg);transform:rotate(-50deg);border-radius:50px 0 0 0\n    /* background: pink; */}.left-bottom-sign[data-v-3de02bc8]{position:absolute;left:%?-270?%;bottom:%?-320?%;border:%?100?% solid #d0d1fd;border-radius:50%;padding:%?180?%}.welcome[data-v-3de02bc8]{position:relative;left:%?50?%;top:%?50?%;font-size:%?46?%;color:#555;text-shadow:1px 0 1px rgba(0,0,0,.3)}.input-content[data-v-3de02bc8]{padding:%?60?% %?60?% 0 %?60?%}.input-item[data-v-3de02bc8]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:start;-webkit-align-items:flex-start;align-items:flex-start;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;padding:0 %?30?%;background:#f8f6fc;height:%?120?%;border-radius:4px;margin-bottom:%?50?%}.input-item[data-v-3de02bc8]:last-child{margin-bottom:0}.input-item .tit[data-v-3de02bc8]{height:%?50?%;line-height:%?56?%;font-size:%?26?%;color:#606266}.input-iteminput[data-v-3de02bc8]{height:%?60?%;font-size:%?30?%;background:#f8f6fc;color:#303133;width:100%}.confirm-btn[data-v-3de02bc8]{height:%?76?%;line-height:%?76?%;border-radius:50px;margin-top:%?70?%;background:#fa436a;color:#fff;font-size:%?32?%}.confirm-btn[data-v-3de02bc8]:after{border-radius:100px}.forget-section[data-v-3de02bc8]{font-size:%?26?%;color:#4399fc;text-align:center;margin-top:%?40?%}.register-section[data-v-3de02bc8]{position:absolute;left:0;bottom:%?50?%;width:100%;font-size:%?26?%;color:#606266;text-align:center}.register-section uni-text[data-v-3de02bc8]{color:#4399fc;margin-left:%?10?%}.login-way[data-v-3de02bc8]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;font-size:%?28?%;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;margin-bottom:%?10?%}.login-way .left[data-v-3de02bc8],.login-way .right[data-v-3de02bc8]{margin:%?10?%}.login-way .nav[data-v-3de02bc8]{padding:%?20?% %?20?%;border-radius:%?40?%;border:1px solid #ccc;color:#ccc}.login-way .active[data-v-3de02bc8]{border:1px solid #ff4500;color:#ff4500}.verify-code-selection[data-v-3de02bc8]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;width:100%}.verify-code-selection .btn-get-code[data-v-3de02bc8]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;font-size:%?28?%;border:%?2?% solid #ff4500;color:#ff4500;border-radius:%?20?%;padding:%?4?% %?20?%}body.?%PAGE?%[data-v-3de02bc8]{background:#fff}',""])},f1f8:function(t,e,i){"use strict";i.r(e);var n=i("0b56"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,function(){return n[t]})}(o);e["default"]=a.a},f6e5:function(t,e,i){"use strict";var n=i("185e"),a=i.n(n);a.a}}]);