import Vue from 'vue'
import Vuex from 'vuex'

import http from '@/common/http'
Vue.use(Vuex)
import user from './modules/user'
import product from './modules/product'
import cart from './modules/cart'
import order from './modules/order'
const modules = {
	user,
	product,
	cart,
	order
}
const store = new Vuex.Store({
	modules,
	state: {
		home_recommend_products: [],
		areas: null
	},
	mutations: {
		SAVE_HOME_PRODUCTS(state, data) {
			let list = data.home_recommend_products
			let home_recommend_products = []
			list.forEach((item, index) => {
				if (item.products.length) home_recommend_products.push(item)
			})
			state.home_recommend_products = home_recommend_products
		},
		SAVE_AREAS(state, data) {
			state.areas = data
		}
	},
	actions: {
		getHomeProducts({ commit }) {
			return new Promise((resolve, reject) => {
				http.get('home.products').then(res => {
					commit('SAVE_HOME_PRODUCTS', res.data)
					resolve(res)
				}).catch(e => {
					reject(e)
				})
			})
		},
		getAreas({ state, commit }) {
			return new Promise((resolve, reject) => {
				if (state.areas) resolve(state.areas)
				else {
					http.get('index.areas').then(res => {
						commit('SAVE_AREAS', res.data)
						resolve(res.data)
					}).catch(e => { reject(e) })
				}
			})
		}
	}
})

export default store
