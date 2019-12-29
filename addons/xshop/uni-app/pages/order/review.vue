<template>
	<view class="review-content">
		<view class="cell-item">
			<text>宝贝评分</text>
			<uni-rate :value="form.star" @change="starChange"></uni-rate>
		</view>
		<view class="cell-item item-content">
			<textarea v-model="form.content" placeholder="评价内容" />
		</view>
		<view class="footer">
			<button type="warn" @click="submit">提交</button>
		</view>
	</view>
</template>

<script>
	import { uniRate } from '@dcloudio/uni-ui';
	export default {
		components: {
			uniRate
		},
		data() {
			return {
				form: {
					id: null,
					star: 5
				},
				state: 0
			}
		},
		onLoad(option) {
			this.form.id = option.id
			this.state = option.state
		},
		methods: {
			starChange(e) {
				this.form.star = e.value
			},
			submit() {
				this.$http.post('order.review', this.form).then(res => {
					uni.redirectTo({
						url: '/pages/order/order?state=' + this.state
					})
				})
			}
		}
	}
</script>

<style lang="scss">
	.review-content {
		font-size: $font-base;
		padding: 10upx 20upx;
		.cell-item {
			display: flex;
			flex-direction: row;
			align-items: center;
		}
		.item-content {
			margin-top: 10upx;
			textarea {
				border: 1px solid #eee;
				border-radius: 10upx;
				flex: 1;
				padding: 10upx;
				
			}
		}
		.footer {
			position: fixed;
			bottom: 20upx;
			width: 100%;
			left: 0;
			
		}
	}
</style>
