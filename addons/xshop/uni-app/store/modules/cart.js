import http  from '@/common/http'
import Vue from 'vue'
const state = {
	products: [],
	isTempProduct: 0,
	tempProducts: [],
	selectedAmount: 0
}


const mutations = {
	SAVE_CART_PRODUCTS(state, data) {
		data.forEach((item, index) => {
			data[index].checked = true
		})
		if (state.isTempProduct) {
			state.tempProducts = data
		} else {
			state.products = []
			setTimeout(() => {
				state.products = data
			}, 2)
			// state.products = data	
		}
	},
	SAVE_CART_PRODUCT_QUANTITY(state, data) {
		state.products[data.index].quantity = data.number
	},
	CART_PRODUCT_CHECK_TOGGLE(state, index) {
		state.products[index].checked = !state.products[index].checked
	},
	CART_PRODUCT_CHECK_ALL(state, checked) {
		state.products.forEach((item, index) => {
			state.products[index].checked = !checked
		})
	},
	SAVE_IS_TEMP_PRODUCT(state, type) {
		if (type) state.isTempProduct = 1
		else state.isTempProduct = 0
	}
}

const actions = {
	cartAddProduct(ctx, form) {
		return new Promise((resolve, reject) => {
			http.post('cart.add', form).then(res => {
				resolve(res)
			}).catch(e => { reject(e) })
		})
	},
	cartUpdateProduct(ctx, form) {
		return new Promise((resolve, reject) => {
			http.post('cart.update', form).then(res => {
				resolve(res)
			}).catch(e => { reject(e) })
		})
	},
	cartUpdateStatus(ctx, form) {
		return new Promise((resolve, reject) => {
			http.post('cart.status', form).then(res => {
				resolve(res)
			}).catch(e => { reject(e) })
		})
	},
	getCartProducts({ commit }, form) {
		return new Promise((resolve, reject) => {
			http.get('cart.list', form).then(res => {
				let isTemp = form ? form.sku_id : 0;
				isTemp = isTemp ? 1 : 0
				commit('SAVE_IS_TEMP_PRODUCT', isTemp)
				commit('SAVE_CART_PRODUCTS', res.data)
				resolve(res)
			}).catch(e => { reject(e) })
		})
	},
	clearCartProducts({ commit }, form) {
		return new Promise((resolve, reject) => {
			http.post('cart.clear').then(res => {
				commit('SAVE_CART_PRODUCTS', [])
				resolve(res)
			}).catch(e => {reject(e)})
		})
	}
}

export default {
	state,
	mutations,
	actions
}