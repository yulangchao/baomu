<template>
	<view class="container">
		<!-- 空白页 -->
		<view v-if="cartList.length == 0" class="empty">
			<image src="/static/emptyCart.jpg" mode="aspectFit"></image>
			<view v-if="hasLogin" class="empty-tips">
				空空如也
				<navigator class="navigator" v-if="hasLogin" url="../index/index" open-type="switchTab">随便逛逛></navigator>
			</view>
			<view v-else class="empty-tips">
				空空如也
				<view class="navigator" @click="navToLogin">去登陆></view>
			</view>
		</view>
		<view v-else>
			<!-- 列表 -->
			<view class="cart-list">
				<block v-for="(item, index) in cartList" :key="item.id">
					<view class="cart-item" :class="{'b-b': index!==cartList.length-1}">
						<view class="image-wrapper">
							<image :src="item.product && item.product.image[0]" class="loaded" mode="aspectFill" lazy-load @click="goProductDetail(item)"></image>
							<view class="yticon icon-xuanzhong2 checkbox" :class="{checked: item.checked}" @click="check('item', index)"></view>
						</view>
						<view class="item-right">
							<text class="clamp title" @click="goProductDetail(item)">{{item.product && item.product.title || '[商品不存在]'}}</text>
							<text class="attr">{{item.product && item.sku.value}}</text>
							<view class="price-box">
								<text class="price">¥{{item.product && item.sku.price || 0}}</text>
								<!-- <text class="out-stock" v-if="item.quantity>item.sku.stock">仅剩{{item.sku.stock}}件</text> -->
							</view>

							<uni-number-box class="step" :min="1" :max="item.sku.stock" :value="item.quantity" :isMax="item.quantity>=item.sku.stock?true:false"
							 :isMin="item.quantity===1" :index="index" @eventChange="numberChange"></uni-number-box>
						</view>
						<text class="del-btn yticon icon-fork" @click="deleteCartItem(index)"></text>
					</view>
				</block>
			</view>
			<!-- 底部菜单栏 -->
			<view class="action-section">
				<view class="checkbox">
					<image :src="allChecked?'/static/selected.png':'/static/select.png'" mode="aspectFit" @click="check('all')"></image>
					<view class="clear-btn" :class="{show: allChecked}" @click="clearCart">
						清空
					</view>
				</view>
				<view class="total-box">
					<text class="price">¥{{total}}</text>
					<!-- <text class="coupon">
						已优惠
						<text>74.35</text>
						元
					</text> -->
				</view>
				<button type="primary" class="no-border confirm-btn" @click="createOrder">去结算</button>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		mapState,
		mapActions,
		mapMutations
	} from 'vuex';
	import uniNumberBox from '@/components/uni-number-box.vue'
	export default {
		components: {
			uniNumberBox
		},
		data() {
			return {
				// allChecked: true,
				// empty: false, //空白页现实  true|false
			};
		},
		onLoad() {
			this.getCartProducts()
		},
		watch: {
			
		},
		computed: {
			...mapState({
				user: state => state.user.userinfo,
				cartList: state => state.cart.products
			}),
			hasLogin() {
				return this.user.id ? true : false
			},
			total() {
				let total = 0
				this.cartList.forEach((item, index) => {
					if (item.checked) total += parseFloat(item.quantity) * parseFloat(item.sku.price)
					
				})
				return total.toFixed(2)
			},
			allChecked() {
				let res = true
				this.cartList.forEach((item, index) => {
					if (!item.checked) res = false
				})
				return res
			}
		},
		methods: {
			...mapActions(['getCartProducts', 'cartUpdateProduct', 'cartUpdateStatus', 'clearCartProducts']),
			...mapMutations(['CART_PRODUCT_CHECK_TOGGLE', 'CART_PRODUCT_CHECK_ALL', 'SAVE_CART_PRODUCT_QUANTITY']),
			navToLogin() {
				uni.navigateTo({
					url: '/pages/public/login'
				})
			},
			//选中状态处理
			check(type, index) {
				if (type === 'item') {
					this.CART_PRODUCT_CHECK_TOGGLE(index)
				} else {
					this.CART_PRODUCT_CHECK_ALL(this.allChecked)
				}
			},
			//数量
			numberChange(data) {
				let item = this.cartList[data.index]
				let form = {
					sku_id: item.sku.id,
					quantity: data.number
				}
				this.cartUpdateProduct(form).then(res => {
					this.SAVE_CART_PRODUCT_QUANTITY(data)
				}).catch(e => {})
			},
			//删除
			deleteCartItem(index) {
				uni.showLoading()
				this.$http.post('cart.delete', {ids: [this.cartList[index].id]}).then(res => {
					uni.hideLoading()
					this.getCartProducts()
				})
			},
			goProductDetail(item) {
				if (!item.product) return
				uni.navigateTo({
					url: '/pages/product/product?id=' + item.product.id
				})
			},
			//清空
			clearCart() {
				uni.showModal({
					content: '清空购物车？',
					success: (e) => {
						if (e.confirm) {
							uni.showLoading()
							this.clearCartProducts().then(res => {
								uni.hideLoading()
							}).catch(e => uni.hideLoading())
						}
					}
				})
			},
			//创建订单
			createOrder() {
				let data = [];
				this.cartList.forEach(item => {
					if (!item.product) {
						uni.showToast({
							title: '商品不存在',
							icon: 'none'
						})
						return
					}
					if (item.checked) {
						data.push(item.id)
					}
				})
				let form = {
					ids: data
				}
				this.cartUpdateStatus(form).then(res => {
					uni.navigateTo({
						url: '/pages/order/createOrder'
					})
				})

			}
		},
		onPullDownRefresh() {
			this.getCartProducts().then(res => {
					uni.stopPullDownRefresh()
			}).catch(e => uni.stopPullDownRefresh())
		}
	}
</script>

<style lang='scss'>
	.container {
		padding-bottom: 134upx;

		/* 空白页 */
		.empty {
			position: fixed;
			left: 0;
			top: 0;
			width: 100%;
			height: 100vh;
			padding-bottom: 100upx;
			display: flex;
			justify-content: center;
			flex-direction: column;
			align-items: center;
			background: #fff;

			image {
				width: 240upx;
				height: 160upx;
				margin-bottom: 30upx;
			}

			.empty-tips {
				display: flex;
				font-size: $font-sm+2upx;
				color: $font-color-disabled;

				.navigator {
					color: $uni-color-primary;
					margin-left: 16upx;
				}
			}
		}
	}

	/* 购物车列表项 */
	.cart-item {
		display: flex;
		position: relative;
		padding: 30upx 40upx;

		.image-wrapper {
			width: 230upx;
			height: 230upx;
			flex-shrink: 0;
			position: relative;

			image {
				border-radius: 8upx;
			}
		}

		.checkbox {
			position: absolute;
			left: -16upx;
			top: -16upx;
			z-index: 8;
			font-size: 44upx;
			line-height: 1;
			padding: 4upx;
			color: $font-color-disabled;
			background: #fff;
			border-radius: 50px;
		}

		.item-right {
			display: flex;
			flex-direction: column;
			flex: 1;
			overflow: hidden;
			position: relative;
			padding-left: 30upx;

			.title,
			.price {
				font-size: $font-base + 2upx;
				color: $font-color-dark;
				height: 40upx;
				line-height: 40upx;
			}

			.attr {
				font-size: $font-sm + 2upx;
				color: $font-color-light;
				height: 50upx;
				line-height: 50upx;
			}

			.price-box {
				display: flex;
				justify-content: space-between;
				align-items: center;

				.out-stock {
					color: red;
					font-size: $font-base;
				}
			}

			.price {
				height: 50upx;
				line-height: 50upx;
			}

		}

		.del-btn {
			padding: 4upx 10upx;
			font-size: 34upx;
			height: 50upx;
			color: $font-color-light;
		}
	}

	/* 底部栏 */
	.action-section {
		/* #ifdef H5 */
		margin-bottom: 100upx;
		/* #endif */
		position: fixed;
		left: 30upx;
		bottom: 30upx;
		z-index: 95;
		display: flex;
		align-items: center;
		width: 690upx;
		height: 100upx;
		padding: 0 30upx;
		background: rgba(255, 255, 255, .9);
		box-shadow: 0 0 20upx 0 rgba(0, 0, 0, .5);
		border-radius: 16upx;

		.checkbox {
			height: 52upx;
			position: relative;

			image {
				width: 52upx;
				height: 100%;
				position: relative;
				z-index: 5;
			}
		}

		.clear-btn {
			position: absolute;
			left: 26upx;
			top: 0;
			z-index: 4;
			width: 0;
			height: 52upx;
			line-height: 52upx;
			padding-left: 38upx;
			font-size: $font-base;
			color: #fff;
			background: $font-color-disabled;
			border-radius: 0 50px 50px 0;
			opacity: 0;
			transition: .2s;

			&.show {
				opacity: 1;
				width: 120upx;
			}
		}

		.total-box {
			flex: 1;
			display: flex;
			flex-direction: column;
			text-align: right;
			padding-right: 40upx;

			.price {
				font-size: $font-lg;
				color: $font-color-dark;
			}

			.coupon {
				font-size: $font-sm;
				color: $font-color-light;

				text {
					color: $font-color-dark;
				}
			}
		}

		.confirm-btn {
			padding: 0 38upx;
			margin: 0;
			border-radius: 100px;
			height: 76upx;
			line-height: 76upx;
			font-size: $font-base + 2upx;
			background: $uni-color-primary;
			box-shadow: 1px 2px 5px rgba(217, 60, 93, 0.72)
		}
	}

	/* 复选框选中状态 */
	.action-section .checkbox.checked,
	.cart-item .checkbox.checked {
		color: $uni-color-primary;
	}
</style>
