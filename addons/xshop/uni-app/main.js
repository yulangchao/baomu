import Vue from 'vue'
import store from './store'
import App from './App'
import { HttpWidget } from '@/common/request/index.js'
import parseHtml from '@/common/parseHtml.js'
import tools from '@/common/tools'
import meRouter from '@/common/merouter'
// #ifdef H5
import wxApi from '@/common/wxApi'
// #endif
import api from '@/common/request/apis/index'
Vue.use(new HttpWidget())

const msg = (title, duration=1500, mask=false, icon='none')=>{
	if(Boolean(title) === false){
		return;
	}
	uni.showToast({
		title,
		duration,
		mask,
		icon
	});
}

const prePage = ()=>{
	let pages = getCurrentPages();
	let prePage = pages[pages.length - 2];
	// #ifdef H5
	return prePage;
	// #endif
	return prePage.$vm;
}

Date.prototype.Format = function(fmt)   
{
  var o = {   
    "M+" : this.getMonth()+1,                 //月份   
    "d+" : this.getDate(),                    //日   
    "h+" : this.getHours(),                   //小时   
    "m+" : this.getMinutes(),                 //分   
    "s+" : this.getSeconds(),                 //秒   
    "q+" : Math.floor((this.getMonth()+3)/3), //季度   
    "S"  : this.getMilliseconds()             //毫秒   
  };   
  if(/(y+)/.test(fmt))   
    fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));   
  for(var k in o)   
    if(new RegExp("("+ k +")").test(fmt))   
  fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));   
  return fmt;   
}  

Vue.config.productionTip = false
Vue.prototype.$fire = new Vue();
Vue.prototype.$store = store;
Vue.prototype.$api = {msg, prePage};
Vue.prototype.$parseHtml = parseHtml
Vue.prototype.$tools = tools
Vue.prototype.$meRouter = meRouter
// #ifdef H5
Vue.prototype.$wxApi = wxApi
// #endif

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()