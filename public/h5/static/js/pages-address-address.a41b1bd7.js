(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-address-address"],{"55a0":function(t,e,i){"use strict";var a=i("faae"),n=i.n(a);n.a},"6d6a":function(t,e,i){e=t.exports=i("2350")(!1),e.push([t.i,'@charset "UTF-8";\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */uni-page-body[data-v-5907522f]{padding-bottom:%?120?%}.content[data-v-5907522f]{position:relative}.list[data-v-5907522f]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding:%?20?% %?30?%;background:#fff;position:relative}.wrapper[data-v-5907522f]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-flex:1;-webkit-flex:1;flex:1}.address-box[data-v-5907522f]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.address-box .tag[data-v-5907522f]{font-size:%?24?%;color:#fa436a;margin-right:%?10?%;background:#fffafb;border:1px solid #ffb4c7;border-radius:%?4?%;padding:%?4?% %?10?%;line-height:1}.address-box .address[data-v-5907522f]{font-size:%?28?%;color:#303133;-webkit-box-flex:1;-webkit-flex:1;flex:1}.u-box[data-v-5907522f]{font-size:%?28?%;color:#909399;margin-top:%?16?%}.u-box .name[data-v-5907522f]{margin-right:%?30?%}.icon-iconfontshanchu1[data-v-5907522f]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;height:%?80?%;font-size:%?40?%;color:#909399;padding-left:%?30?%}.add-btn[data-v-5907522f]{position:fixed;left:%?30?%;right:%?30?%;bottom:%?16?%;z-index:95;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;width:%?690?%;height:%?80?%;font-size:%?32?%;color:#fff;background-color:#fa436a;border-radius:%?10?%;box-shadow:1px 2px 5px rgba(219,63,96,.4)}.del-btn[data-v-5907522f]{color:#fa436a}',""])},ad83:function(t,e,i){"use strict";var a=i("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=a(i("f499")),s=a(i("cebc")),d=i("2f62"),o=a(i("bb10")),r={components:{empty:o.default},data:function(){return{source:0,addressList:[],option:{}}},onLoad:function(t){this.source=t.source,this.option=t,this.getUserAddress()},computed:(0,s.default)({},(0,d.mapState)({address:function(t){return t.user.address}})),methods:(0,s.default)({},(0,d.mapActions)(["getUserAddress"]),{checkAddress:function(t){1==this.source&&(this.option.address_id=t.id,uni.navigateTo({url:"/pages/order/createOrder?"+this.$tools.queryStringify(this.option)}))},addAddress:function(t,e){uni.navigateTo({url:"/pages/address/addressManage?type=".concat(t,"&data=").concat((0,n.default)(e))})},delAddress:function(t){var e=this;uni.showModal({title:"提示",content:"确定删除地址吗？",success:function(i){i.confirm&&e.$http.post("user.address.del",{address_id:t.id}).then(function(t){e.getUserAddress()})}})},refreshList:function(t,e){this.addressList.unshift(t)}})};e.default=r},d984:function(t,e,i){"use strict";i.r(e);var a=i("f634"),n=i("e776");for(var s in n)"default"!==s&&function(t){i.d(e,t,function(){return n[t]})}(s);i("55a0");var d=i("2877"),o=Object(d["a"])(n["default"],a["a"],a["b"],!1,null,"5907522f",null);e["default"]=o.exports},e776:function(t,e,i){"use strict";i.r(e);var a=i("ad83"),n=i.n(a);for(var s in a)"default"!==s&&function(t){i.d(e,t,function(){return a[t]})}(s);e["default"]=n.a},f634:function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"content b-t"},[t._l(t.address,function(e,a){return i("v-uni-view",{key:a,staticClass:"list b-b",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.checkAddress(e)}}},[i("v-uni-view",{staticClass:"wrapper"},[i("v-uni-view",{staticClass:"address-box"},[1==e.is_default?i("v-uni-text",{staticClass:"tag"},[t._v("默认")]):t._e(),i("v-uni-text",{staticClass:"address"},[t._v(t._s(e.address)+" "+t._s(e.street))])],1),i("v-uni-view",{staticClass:"u-box"},[i("v-uni-text",{staticClass:"name"},[t._v(t._s(e.contactor_name))]),i("v-uni-text",{staticClass:"mobile"},[t._v(t._s(e.phone))])],1)],1),i("v-uni-text",{staticClass:"yticon icon-bianji",on:{click:function(i){i.stopPropagation(),arguments[0]=i=t.$handleEvent(i),t.addAddress("edit",e)}}}),i("v-uni-text",{staticClass:"del-btn yticon icon-iconfontshanchu1",on:{click:function(i){i.stopPropagation(),arguments[0]=i=t.$handleEvent(i),t.delAddress(e)}}})],1)}),0==t.address.length?i("empty"):t._e(),i("v-uni-button",{staticClass:"add-btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.addAddress("add")}}},[t._v("新增地址")])],2)},n=[];i.d(e,"a",function(){return a}),i.d(e,"b",function(){return n})},faae:function(t,e,i){var a=i("6d6a");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("4e41a940",a,!0,{sourceMap:!1,shadowMode:!1})}}]);