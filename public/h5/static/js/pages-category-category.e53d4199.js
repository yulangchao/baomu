(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-category-category"],{"11d4":function(t,e,i){"use strict";var n=i("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,i("ac6a"),i("96cf");var a=n(i("3b8d")),s={data:function(){return{sizeCalcState:!1,tabScrollTop:0,currentIndex:0,list:[],flist:[],slist:[]}},onLoad:function(){this.loadData()},methods:{loadData:function(){var t=(0,a.default)(regeneratorRuntime.mark(function t(e){var i=this;return regeneratorRuntime.wrap(function(t){while(1)switch(t.prev=t.next){case 0:this.$http.get("category").then(function(t){i.list=t.data,i.list.length&&(i.currentIndex=0,i.getSlist()),e&&e()});case 1:case"end":return t.stop()}},t,this)}));function e(e){return t.apply(this,arguments)}return e}(),getSlist:function(){this.slist=this.list[this.currentIndex].childlist},tabtap:function(t,e){this.currentIndex=e,this.getSlist()},asideScroll:function(t){this.sizeCalcState||this.calcSize();var e=t.detail.scrollTop,i=this.slist.filter(function(t){return t.top<=e}).reverse();i.length>0&&(this.currentId=i[0].pid)},calcSize:function(){var t=0;this.slist.forEach(function(e){var i=uni.createSelectorQuery().select("#main-"+e.id);i.fields({size:!0},function(i){e.top=t,t+=i.height,e.bottom=t}).exec()}),this.sizeCalcState=!0},navToList:function(t){uni.navigateTo({url:"/pages/product/list?cat_id="+t.id})}},onPullDownRefresh:function(){this.loadData(function(){uni.stopPullDownRefresh()})}};e.default=s},"381c":function(t,e,i){e=t.exports=i("2350")(!1),e.push([t.i,'@charset "UTF-8";\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */.content[data-v-e2fb2902],uni-page-body[data-v-e2fb2902]{height:100%;background-color:#f8f8f8}.content[data-v-e2fb2902]{display:-webkit-box;display:-webkit-flex;display:flex}.left-aside[data-v-e2fb2902]{-webkit-flex-shrink:0;flex-shrink:0;width:%?200?%;height:100%;background-color:#fff}.f-item[data-v-e2fb2902]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;width:100%;height:%?100?%;font-size:%?28?%;color:#606266;position:relative}.f-item.active[data-v-e2fb2902]{color:#fa436a;background:#f8f8f8}.f-item.active[data-v-e2fb2902]:before{content:"";position:absolute;left:0;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);height:%?36?%;width:%?8?%;background-color:#fa436a;border-radius:0 4px 4px 0;opacity:.8}.right-aside[data-v-e2fb2902]{-webkit-box-flex:1;-webkit-flex:1;flex:1;overflow:hidden;padding-left:%?20?%}.s-item[data-v-e2fb2902]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;height:%?70?%;padding-top:%?8?%;font-size:%?28?%;color:#303133}.t-list[data-v-e2fb2902]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-flex-wrap:wrap;flex-wrap:wrap;width:100%;background:#fff;padding-top:%?12?%}.t-list[data-v-e2fb2902]:after{content:"";-webkit-box-flex:99;-webkit-flex:99;flex:99;height:0}.t-item[data-v-e2fb2902]{-webkit-flex-shrink:0;flex-shrink:0;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;width:%?176?%;font-size:%?26?%;color:#666;padding-bottom:%?20?%}.t-item uni-image[data-v-e2fb2902]{width:%?140?%;height:%?140?%}body.?%PAGE?%[data-v-e2fb2902]{background-color:#f8f8f8}',""])},"4f74":function(t,e,i){var n=i("381c");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("6effc654",n,!0,{sourceMap:!1,shadowMode:!1})},a413:function(t,e,i){"use strict";var n=i("4f74"),a=i.n(n);a.a},b92f:function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"content"},[i("v-uni-scroll-view",{staticClass:"left-aside",attrs:{"scroll-y":""}},t._l(t.list,function(e,n){return i("v-uni-view",{key:e.id,staticClass:"f-item b-b",class:{active:n===t.currentIndex},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.tabtap(e,n)}}},[t._v(t._s(e.name))])}),1),i("v-uni-scroll-view",{staticClass:"right-aside",attrs:{"scroll-with-animation":"","scroll-y":"","scroll-top":t.tabScrollTop},on:{scroll:function(e){arguments[0]=e=t.$handleEvent(e),t.asideScroll.apply(void 0,arguments)}}},t._l(t.slist,function(e){return i("v-uni-view",{key:e.id,staticClass:"s-list",attrs:{id:"main-"+e.id}},[i("v-uni-text",{staticClass:"s-item"},[t._v(t._s(e.name))]),i("v-uni-view",{staticClass:"t-list"},t._l(e.childlist,function(e,n){return i("v-uni-view",{key:n,staticClass:"t-item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navToList(e)}}},[i("v-uni-image",{attrs:{src:e.image}}),i("v-uni-text",[t._v(t._s(e.name))])],1)}),1)],1)}),1)],1)},a=[];i.d(e,"a",function(){return n}),i.d(e,"b",function(){return a})},d739:function(t,e,i){"use strict";i.r(e);var n=i("b92f"),a=i("f547");for(var s in a)"default"!==s&&function(t){i.d(e,t,function(){return a[t]})}(s);i("a413");var l=i("2877"),o=Object(l["a"])(a["default"],n["a"],n["b"],!1,null,"e2fb2902",null);e["default"]=o.exports},f547:function(t,e,i){"use strict";i.r(e);var n=i("11d4"),a=i.n(n);for(var s in n)"default"!==s&&function(t){i.d(e,t,function(){return n[t]})}(s);e["default"]=a.a}}]);