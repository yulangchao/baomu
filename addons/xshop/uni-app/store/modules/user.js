import http from '@/common/http'
const state = {
	userinfo: {},
	address: []
}
const mutations = {
	SAVE_USERINFO(state, data) {
		state.userinfo = data || {}
	},
	SAVE_ADDRESS(state, data) {
		state.address = data
	}
}
const actions = {
	login({ commit }, form) {
		return new Promise((resolve, reject) => {
			http.post('login', form).then(res => {
				uni.setStorageSync('token', res.data.token)
				commit('SAVE_USERINFO', res.data)
				resolve(res)
			}).catch(e => {
				reject(e)
			})
		})
	},
	logout({ commit }) {
		return new Promise((resolve, reject) => {
			http.post('logout').then(res => {
				uni.removeStorageSync('token')
				commit('SAVE_USERINFO', {})
				resolve()
			}).catch(e => { reject(e) })
		})
	},
	getUserinfo({ commit }) {
		return new Promise((resolve, reject) => {
			http.get('user.info').then(res => {
				commit('SAVE_USERINFO', res.data)
				resolve(res)
			}).catch(e => {
				reject(e)
			})
		})
	},
	getUserAddress({ commit }) {
		return new Promise((resolve, reject) => {
			http.get('user.address').then(res => {
				commit('SAVE_ADDRESS', res.data)
				resolve(res.data)
			}).catch(e => { reject(e) })
		})
	}
}
export default {
	state,
	mutations,
	actions
}