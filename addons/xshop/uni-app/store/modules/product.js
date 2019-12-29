import http from '@/common/http'

const state = {
	
}
const mutations = {
	
}
const actions = {
	getProductInfo({ commit }, form) {
		return new Promise((resolve, reject) => {
			http.get('product.info', form).then(res => {
				resolve(res)
			}).catch(e => {
				reject(e)
			})
		})
	}
}

export default {
	state,
	mutations,
	actions
}