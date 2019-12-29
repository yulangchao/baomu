import http from '@/common/http'
const state = {
	createProducts: []
}
const mutations = {
}
const actions = {
	orderGetList({ commit }, form) {
		return new Promise((resolve, reject) => {
			http.get('order.list', form).then(res => {
				resolve(res)
			}).catch(e => { reject(e) })
		})
	}
}

const getters = {
	createTotal(state, getters, rootState) {
		let list = rootState.cart.products
		console.log(rootState)
		let total = 0
		list.forEach((index, item) => {
			if (item.is_selected) total += item.quantity * item.sku.price
		})
		console.log(total)
		return Number(total.toFix(2))
	}
}

export default {
	state,
	mutations,
	actions,
	getters
}