<template>
	<!-- 优惠券面板 -->
	<view class="coupon-content show" >
		<view class="mask-content">
			<view class="coupon-item" v-for="(item,index) in list" :key="index" @click="showDetail(item)">
				<view class="con">
					<view class="left">
						<text class="title">{{item.coupon && item.coupon.name}} x {{item.usable_count}}</text>
						<text class="time">有效期至{{item.coupon && item.coupon.use_end_time_text}}</text>
					</view>
					<view class="right">
						<text class="price">{{item.coupon && item.coupon.money}}</text>
						<text>满{{item.coupon && item.coupon.order_min_amount}}可用</text>
					</view>

					<view class="circle l"></view>
					<view class="circle r"></view>
				</view>
				<view class="op">
					<text class="tips">{{item.coupon && item.coupon.desc}}</text>
				</view>
			</view>
		</view>
		
		<coupon-detail ref="$popup" :coupon-type="couponType" :detail="detail"></coupon-detail>
	</view>
</template>

<script>
	import couponDetail from './child/couponDetail'
	export default {
		components: {
			couponDetail
		},
		data() {
			return {
				couponVisible: 0,
				list: [],
				detail: [],
				currentCoupon: {},
				couponType: 0
			}
		},
		mounted() {
			this.getCoupon()
		},
		methods: {
			getCoupon() {
				return new Promise((resolve, reject) => {
					uni.showLoading()
					this.$http.post('coupon').then(res => {
						uni.hideLoading()
						this.list = res.data
						resolve(res)
					}).catch(e => {
						uni.hideLoading()
						reject(e)
					})
				})
			},
			showDetail(item) {
				if (item.coupon.type == 0) return
				let form = {
					coupon_id: item.coupon.id
				}
				this.currentCoupon = item
				this.couponType = item.coupon.type
				uni.showLoading()
				this.$http.post('coupon.detail', form).then(res => {
					uni.hideLoading()
					this.detail = res.data
					this.$refs.$popup.open()
				}).catch(e => { uni.hideLoading() })
			},
		},
		onPullDownRefresh() {
			this.getCoupon().then(e => {
				uni.stopPullDownRefresh()
			}).catch(e => {
				uni.stopPullDownRefresh()
			})
		}
		
	}
</script>

<style lang="scss">
	page {
		background: #f3f3f3;
	}
	/* 优惠券面板 */
	
	.coupon-content {
		display: flex;
		

		.mask-content {
			width: 100%;
			background: #f3f3f3;
			transform: translateY(100%);
			transition: .3s;
			//overflow-y: scroll;
		}

		&.none {
			display: none;
		}

		&.show {
			.mask-content {
				transform: translateY(0);
			}
		}
	}

	/* 优惠券列表 */
	.coupon-item {
		display: flex;
		flex-direction: column;
		margin: 20upx 24upx;
		background: #fff;

		.con {
			display: flex;
			align-items: center;
			position: relative;
			height: 120upx;
			padding: 0 30upx;

			&:after {
				position: absolute;
				left: 0;
				bottom: 0;
				content: '';
				width: 100%;
				height: 0;
				border-bottom: 1px dashed #f3f3f3;
				transform: scaleY(50%);
			}
		}

		.left {
			display: flex;
			flex-direction: column;
			justify-content: center;
			flex: 1;
			overflow: hidden;
			height: 100upx;
		}

		.title {
			font-size: 32upx;
			color: $font-color-dark;
			margin-bottom: 10upx;
		}

		.time {
			font-size: 24upx;
			color: $font-color-light;
		}

		.right {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			font-size: 26upx;
			color: $font-color-base;
			height: 100upx;
		}

		.price {
			font-size: 44upx;
			color: $base-color;

			&:before {
				content: '￥';
				font-size: 34upx;
			}
		}


		.circle {
			position: absolute;
			left: -6upx;
			bottom: -10upx;
			z-index: 10;
			width: 20upx;
			height: 20upx;
			background: #f3f3f3;
			border-radius: 100px;

			&.r {
				left: auto;
				right: -6upx;
			}
		}
		.op {
			display: flex;
			justify-content: space-between;
			font-size: 26upx;
			padding: 10upx 20upx;
			.tips {
				font-size: 24upx;
				color: $font-color-light;
				display: flex;
				align-items: center;
			}
			.op-btn {
				border: 2upx solid #fa436a;
				border-radius: 10upx;
				padding: 4upx 20upx;
				background: #fa436a;
				color: #fff;
				//color: #fa436a;
			}
		}
	}
	
</style>
