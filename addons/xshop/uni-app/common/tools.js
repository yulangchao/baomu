export default {
	/**
	 * @param {Array}  rows
	 * @param {Object} filter
	 */
	find_rows(rows, filter, return_index = true) {
		for (let i = 0; i < rows.length; i ++) {
			let res = true
			let item = rows[i]
			for (let key in filter) {
				if (item[key] != filter[key]) res = false
			}
			if (res) return return_index ? index : item
		}
		return return_index ? -1 : null
	},
	
	//设置cookie
	setCookie: function (cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
		var expires = "expires=" + d.toUTCString();
		document.cookie = cname + "=" + cvalue + "; " + expires;
		console.info(document.cookie);
	},
	//获取cookie
	getCookie: function (cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') c = c.substring(1);
			if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
		}
		return "";
	},
	//清除cookie
	clearCookie: function (cname) {
		this.setCookie(cname, "", -1);
	},
	getPlatform: function() {
		let platform = uni.getSystemInfoSync().platform;
		
		// #ifdef H5 
		if (window.navigator && window.navigator.userAgent) {
			const ua = window.navigator.userAgent.toLowerCase()
			if (ua.match(/MicroMessenger/i) == 'micromessenger') {
				platform = 'WX-H5'
			} else platform = 'H5'
		}
		// #endif
		// #ifdef MP-WEIXIN
		platform = 'MP-WEIXIN'
		// #endif
		// #ifdef MP-ALIPAY
		platform = 'MP-ALIPAY'
		// #endif
		// #ifdef MP-BAIDU
		platform = 'MP-BAIDU'
		// #endif
		return platform
	},
	has_addon(name) {
		let appInfo = uni.getStorageSync('appInfo')
		if (!appInfo) return false
		if (!appInfo.plugins) return false
		if (appInfo.plugins.indexOf(name) != -1) return true
		return false
	},
	queryStringify(obj) {
		let res = []
		for (let k in obj) {
			res.push(`${k}=${obj[k]}`)
		}
		return res.join('&')
	}
}