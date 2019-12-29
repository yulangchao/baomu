<template>
	<!-- 优惠券面板 -->
	<view class="mask" :class="couponVisible===0 ? 'none' : couponVisible===1 ? 'show' : ''" @click="toggleMask">
		<view class="mask-content">
			<view class="coupon-item" v-for="(item,index) in list" :key="index">
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
					<text class="tips">{{item.coupon && item.coupon.note}}</text>
					<text class="op-btn" @click.stop="useCoupon(item, index)">使用</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				couponVisible: 0
			}
		},
		props: {
			value: {
				type: String | Number,
				default: 0
			},
			list: {
				type: Array
			}
		},
		watch: {
			value(val) {
				this.couponVisible = val
			},
			couponVisible(val) {
				this.$emit('input', val)
			}
		},
		mounted() {
			this.couponVisible = this.value
		},
		methods: {
			toggleMask(type) {
				let timer = type === 'show' ? 10 : 300;
				let	state = type === 'show' ? 1 : 0;
				this.couponVisible = 2;
				setTimeout(()=>{
					this.couponVisible = state;
				}, timer)
			},
			useCoupon(item, index) {
				this.$emit('change', item.coupon, index)
			}
		}
	}
</script>

<style lang="scss">
	/* 优惠券面板 */
	.mask {
		display: flex;
		align-items: flex-end;
		position: fixed;
		left: 0;
		top: var(--window-top);
		bottom: 0;
		width: 100%;
		background: rgba(0, 0, 0, 0);
		z-index: 9995;
		transition: .3s;

		.mask-content {
			width: 100%;
			max-height: 60vh;
			background: #f3f3f3;
			transform: translateY(100%);
			transition: .3s;
			overflow-y: scroll;
		}

		&.none {
			display: none;
		}

		&.show {
			background: rgba(0, 0, 0, .4);

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
