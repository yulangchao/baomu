/**
 * 接口配置文件
 */
export default {
	'home.products': {
		uri: 'product/getHomeProducts'
	},
	'update': {
		uri: 'index/update'
	},
	'index.config': {
		uri: 'index/getConfig'
	},
	'index.openid': {
		uri: 'index/getOpenId'
	},
	'index.areas': {
		uri: 'area/index'
	},
	'index.code.get': {
		uri: 'index/getVerifyCode'
	},
	'index.code.check' : {
		uri: 'index/checkVerifyCode'
	},
	'index.wxmp.login': {
		uri: 'index/wxMPLogin'
	},
	'index.ttmp.login': {
		uri: 'index/ttMPLogin'
	},
	'index.launch': {
		uri: 'index/launch'
	},
	'index.appInfo': {
		uri: 'index/appInfo'
	},
	
	/** 导航 ↓ */
	
	'nav': {
		uri: 'nav'
	},
	
	/** 导航 ↑ */
	
	/** 商品 ↓ */
	'product.info': {
		uri: 'product'
	},
	'product.list': {
		uri: 'product/getList'
	},
	'product.favorite': {
		uri: 'product/favorite'
	},
	'product.reviews': {
		uri: 'product/getReviews'
	},
	/** 商品 ↑ */
	
	/** 分类 ↓ */
	'category': {
		uri: 'category'
	},
	/** 分类 ↑ */
	
	/** 购物车 ↓ */
	'cart.add': {
		uri: 'cart/add'
	},
	'cart.update': {
		uri: 'cart/edit'
	},
	'cart.status': {
		uri: 'cart/updateStatus'
	},
	'cart.list': {
		uri: 'cart'
	},
	'cart.delete': {
		uri: 'cart/delete'
	},
	'cart.clear': {
		uri: 'cart/clear'
	},
	/** 购物车 ↑ */
	
	
	/** 用户 ↓ */
	'user.info': {
		uri: 'user'
	},
	'user.info.edit': {
		uri: 'user/editInfo'
	},
	'user.address': {
		uri: 'user/getAddress'
	},
	'user.address.edit': {
		uri: 'user/editAddress'
	},
	'user.address.del': {
		uri: 'user/delAddress'
	},
	'user.viewlist': {
		uri: 'user/viewList'
	},
	'user.favorite': {
		uri: 'user/favorite'
	},
	/** 用户 ↑ */
	
	/** 订单 ↓ */
	'order.list': {
		uri: 'order'
	},
	'order.create': {
		uri: 'order/add'
	},
	'order.info': {
		uri: 'order/info'
	},
	'order.getPrice': {
		uri: 'order/getPrice'
	},
	'order.receive': {
		uri: 'order/receive'
	},
	'order.cancel': {
		uri: 'order/cancel'
	},
	'order.del': {
		uri: 'order/del'
	},
	'order.review': {
		uri: 'order/review'
	},
	/** 订单 ↑ */
	
	/** 支付 */
	pay: {
		uri: 'pay'
	},
	
	login: {
		uri: 'index/login'
	},
	logout: {
		uri: 'index/logout'
	}
	

    // 可以设置接口是否需要授权访问
    // login: {
    //     uri: "login",
    //     auth: false
    // },

    // 支持参数
    // 'resource.detail': {
    //     uri: 'resources/:id',
    //     auth: 'required',
    // }
};
