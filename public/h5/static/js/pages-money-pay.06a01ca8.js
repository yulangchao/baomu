(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-money-pay"],{8852:function(e,t,i){t=e.exports=i("2350")(!1),t.push([e.i,'@charset "UTF-8";\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */.app[data-v-06d0187c]{width:100%}.price-box[data-v-06d0187c]{background-color:#fff;height:%?265?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center;font-size:%?28?%;color:#909399}.price-box .price[data-v-06d0187c]{font-size:%?50?%;color:#303133;margin-top:%?12?%}.price-box .price[data-v-06d0187c]:before{content:"\\FFE5";font-size:%?40?%}.pay-type-list[data-v-06d0187c]{margin-top:%?20?%;background-color:#fff;padding-left:%?60?%}.pay-type-list .type-item[data-v-06d0187c]{height:%?120?%;padding:%?20?% 0;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding-right:%?60?%;font-size:%?30?%;position:relative}.pay-type-list .icon[data-v-06d0187c]{width:%?100?%;font-size:%?52?%}.pay-type-list .icon-erjiye-yucunkuan[data-v-06d0187c]{color:#fe8e2e}.pay-type-list .icon-weixinzhifu[data-v-06d0187c]{color:#36cb59}.pay-type-list .icon-alipay[data-v-06d0187c]{color:#01aaef}.pay-type-list .tit[data-v-06d0187c]{font-size:%?32?%;color:#303133;margin-bottom:%?4?%}.pay-type-list .con[data-v-06d0187c]{-webkit-box-flex:1;-webkit-flex:1;flex:1;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;font-size:%?24?%;color:#909399}.mix-btn[data-v-06d0187c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;width:%?630?%;height:%?80?%;margin:%?80?% auto %?30?%;font-size:%?32?%;color:#fff;background-color:#fa436a;border-radius:%?10?%;box-shadow:1px 2px 5px rgba(219,63,96,.4)}',""])},"9fdf":function(e,t,i){"use strict";i.r(t);var n=i("e073"),a=i.n(n);for(var o in n)"default"!==o&&function(e){i.d(t,e,function(){return n[e]})}(o);t["default"]=a.a},ad0f:function(e,t,i){var n=i("8852");"string"===typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);var a=i("4f06").default;a("721908eb",n,!0,{sourceMap:!1,shadowMode:!1})},b63c:function(e,t,i){"use strict";i.r(t);var n=i("bf86"),a=i("9fdf");for(var o in a)"default"!==o&&function(e){i.d(t,e,function(){return a[e]})}(o);i("ba45");var c=i("2877"),r=Object(c["a"])(a["default"],n["a"],n["b"],!1,null,"06d0187c",null);t["default"]=r.exports},ba45:function(e,t,i){"use strict";var n=i("ad0f"),a=i.n(n);a.a},bf86:function(e,t,i){"use strict";var n=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("v-uni-view",{staticClass:"app"},[i("v-uni-view",{staticClass:"price-box"},[i("v-uni-text",[e._v("支付金额")]),i("v-uni-text",{staticClass:"price"},[e._v(e._s(e.orderInfo.order_price))])],1),i("v-uni-view",{staticClass:"pay-type-list"},[e.payTypeArr.indexOf("wechat")>-1?i("v-uni-view",{staticClass:"type-item b-b",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.changePayType("wechat")}}},[i("v-uni-text",{staticClass:"icon yticon icon-weixinzhifu"}),i("v-uni-view",{staticClass:"con"},[i("v-uni-text",{staticClass:"tit"},[e._v("微信支付")])],1),i("v-uni-label",{staticClass:"radio"},[i("v-uni-radio",{attrs:{value:"",color:"#fa436a",checked:"wechat"==e.payType||1==e.payTypeArr.length}})],1)],1):e._e(),e.payTypeArr.indexOf("alipay")>-1?i("v-uni-view",{staticClass:"type-item b-b",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.changePayType("alipay")}}},[i("v-uni-text",{staticClass:"icon yticon icon-alipay"}),i("v-uni-view",{staticClass:"con"},[i("v-uni-text",{staticClass:"tit"},[e._v("支付宝支付")])],1),i("v-uni-label",{staticClass:"radio"},[i("v-uni-radio",{attrs:{value:"",color:"#fa436a",checked:"alipay"==e.payType||1==e.payTypeArr.length}})],1)],1):e._e()],1),i("v-uni-text",{staticClass:"mix-btn",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.confirm.apply(void 0,arguments)}}},[e._v("确认支付")])],1)},a=[];i.d(t,"a",function(){return n}),i.d(t,"b",function(){return a})},e073:function(e,t,i){"use strict";var n=i("288e");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,i("96cf");var a=n(i("3b8d")),o={data:function(){return{payType:"wechat",orderInfo:{},openId:null}},computed:{payTypeArr:function(){var e=[];return e=this.$wxApi.isweixin()?["wechat"]:["alipay"],e}},onReady:function(){var e=(0,a.default)(regeneratorRuntime.mark(function e(){var t,i,n,a,o;return regeneratorRuntime.wrap(function(e){while(1)switch(e.prev=e.next){case 0:if(!this.$wxApi.isweixin()||uni.getStorageSync("openId")){e.next=9;break}if(uni.getStorageSync("appId")){e.next=6;break}return e.next=4,this.$http.post("index.config",{code:["h5_appId"]});case 4:t=e.sent,uni.setStorageSync("appId",t.data.xshop_h5_appid);case 6:i=encodeURI(this.$wxApi.redirectUrl(location.href)),n=uni.getStorageSync("appId"),location.href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".concat(n,"&redirect_uri=").concat(i,"&response_type=code&scope=snsapi_base&state=1#wechat_redirect");case 9:a=this.$route.query.payload,o=uni.getStorageSync("openId"),!o&&a&&(a=JSON.parse(a),(a.state=!o)&&this.$http.post("index.openid",{code:a.code}).then(function(e){uni.setStorageSync("openId",e.data)}));case 12:case"end":return e.stop()}},e,this)}));function t(){return e.apply(this,arguments)}return t}(),onLoad:function(e){var t=this;this.order_sn=e.order_sn,this.getData(),this.$on("onPaySuccess",function(e,i){t.paySuccess()}),this.$on("onPayFail",function(e,t){uni.showToast({title:e,icon:"none"})})},methods:{getData:function(){var e=this;this.$http.get("order.info",{order_sn:this.order_sn}).then(function(t){e.orderInfo=t.data}).catch(function(e){})},changePayType:function(e){this.payType=e},confirm:function(){1==this.payTypeArr.length&&(this.payType=this.payTypeArr[0]),this.confirmH5WEIXIN()},confirmH5WEIXIN:function(){var e=this,t=this.$wxApi.isweixin()?"mp":"wap",i={order_sn:this.order_sn,pay_type:this.payType,pay_method:t,appId:uni.getStorageSync("appId"),openId:uni.getStorageSync("openId")};uni.showLoading(),this.$http.post("pay",i).then(function(t){if(uni.hideLoading(),t.data){var i="string"==typeof t.data?JSON.parse(t.data):t.data;if("undefined"!=typeof WeixinJSBridge)return e.onBridgeReady(i);document.addEventListener?document.addEventListener("WeixinJSBridgeReady",e.onBridgeReady(i),!1):document.attachEvent&&(document.attachEvent("WeixinJSBridgeReady",e.onBridgeReady(i)),document.attachEvent("onWeixinJSBridgeReady",e.onBridgeReady(i)))}}).catch(function(e){uni.hideLoading()})},onBridgeReady:function(e){var t=this;WeixinJSBridge.invoke("getBrandWCPayRequest",{appId:e.appId,timeStamp:e.timeStamp,nonceStr:e.nonceStr,package:e.package,signType:e.signType,paySign:e.paySign},function(e){return"get_brand_wcpay_request:ok"==e.err_msg?(t.$emit("onPaySuccess"),!0):(t.$emit("onPayFail","支付失败"),!1)})},paySuccess:function(){uni.redirectTo({url:"/pages/money/paySuccess"})}}};t.default=o}}]);