import wx from 'weixin-js-sdk'
import config from '@/common/config'
const wxApi = {
	/*
	 * [isweixin 判断是否微信浏览器]
	 */
	isweixin() {
		const ua = window.navigator.userAgent.toLowerCase()
		if (ua.match(/MicroMessenger/i) == 'micromessenger') {
			return true
		} else {
			return false
		}
	},

	/*
	 * getConfigRes： 获取config微信配置
	 */
	getConfigRes(title, imgUrl, desc) {
		// TODO:
		let that = this;
		let url = window.location.href.split('#')[0] + '?#' + window.location.href.split('#')[1];
		// jssdkConfig(window.location.href.split('#')[0]).then(res => {
		// 	if (res) {
		// 		let wechatConfig = res.config;
		// 		that.wxRegister(wechatConfig, title, imgUrl, desc);
		// 	}
		// })
	},

	/*
	 * [wxRegister 微信Api初始化]
	 * @param  {Function} callback [ready回调函数]
	 */
	wxRegister(config, title, imgUrl, desc, url, callback) {		
		wx.config({
			debug: false, // 开启调试模式
			appId: config.app_id, // 必填，公众号的唯一标识
			timestamp: config.timestamp, // 必填，生成签名的时间戳
			nonceStr: config.nonceStr, // 必填，生成签名的随机串
			signature: config.signature, // 必填，签名，见附录1
			jsApiList: [
				'onMenuShareTimeline', // 获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
				'onMenuShareAppMessage', // 获取“分享给朋友”按钮点击状态及自定义分享内容接口
				'onMenuShareQQ', // 获取“分享到QQ”按钮点击状态及自定义分享内容接口
				'onMenuShareWeibo' // 获取“分享到微博”按钮点击状态及自定义分享内容接口
			] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
		})

		wx.ready(function () {						
			imgUrl = imgUrl ? imgUrl : require('../static/arc.png');
			let link = url
			desc = desc ? desc : '暂无描述';
			wx.onMenuShareAppMessage({
				title: title, // 分享标题
				imgUrl: imgUrl, // 分享图标
				desc: desc, // 分享描述
				link: link,
				success: function(e) {
				},
				cancel: function(e) {
				},
				error: function (e) {
				}
			})

			wx.onMenuShareTimeline({
				title: title, // 分享标题
				link: link, // 分享链接
				imgUrl: imgUrl, // 分享图标
				success(e) {
					// 用户成功分享后执行的回调函数
				},
				cancel(e) {
					// 用户取消分享后执行的回调函数
				},
				error(e) {
				}
			})

			wx.onMenuShareQQ({
				title: title, // 分享标题
				link: link, // 分享链接
				imgUrl: imgUrl, // 分享图标
				desc: desc,
				success() {
					// 用户成功分享后执行的回调函数
				},
				cancel() {
					// 用户取消分享后执行的回调函数
				}
			})

			wx.onMenuShareWeibo({
				title: title, // 分享标题
				link: link, // 分享链接
				imgUrl: imgUrl, // 分享图标
				success() {
					// 用户成功分享后执行的回调函数
				},
				cancel() {
					// 用户取消分享后执行的回调函数
				}
			})
		});

		wx.error(res => {
			// alert('验证失败的信息:' + res.errMsg);
			console.log(res, 'error');
		});
	},
	redirectUrl(url) {
		return window.location.href.split('#')[0] + 'redirect.html?redirect=' + encodeURIComponent(url);
	}
}

export default wxApi
