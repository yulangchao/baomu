<template>
	
	<uni-popup ref="$popup" class="popup-content" type="bottom">
		<view class="">
			
		<view class="content" v-if="couponType == 2">
			<text class="h3">以下商品可用</text>
			<view class="item" v-for="(item, index) in detail" :key="index">
				<view class="product" @click="goProduct(item)">
					<view class="image">
						<image v-if="item.image && item.image[0]" :src="item.image && item.image[0]" mode="aspectFill"></image>
					</view>
					<view class="desc">
						<text class="title">{{item.title}}</text>
						<text class="price">￥{{item.price}}</text>
					</view>
				</view>
			</view>
		</view>
		<view class="category-content" v-if="couponType == 1">
			<text class="h3">以下分类可用</text>
			<view class="item" v-for="(item, index) in detail" :key="index" @click="goCategory(item)">
				<text>{{item.name}}</text>
			</view>
		</view>
		</view>
	</uni-popup>
</template>

<script>
	import {uniPopup} from '@dcloudio/uni-ui'
	export default {
		components: {
			uniPopup
		},
		data() {
			return {
			}
		},
		props: {
			couponType: {
				type: String | Number,
				default: 0
			},
			detail: {
				type: Array
			}
		},
		methods: {
			goProduct(item) {
				uni.navigateTo({
					url: '/pages/product/product?id=' + item.id
				})
			},
			goCategory(item) {
				uni.navigateTo({
					url: '/pages/product/list?cat_id=' + item.id
				})
			},
			open() {
				this.$refs.$popup.open()
			},
			close() {
				this.$refs.$popup.close()
			}
		}
	}
</script>

<style lang="scss">
	.h3 {
		color: #444;
		display: flex;
		flex: 1;
		justify-content: center;
		align-items: center;
		border-bottom: #eee;
		font-size: 32upx;
		margin: 20upx;
	}
	.popup-content {
		font-size: 26upx;
		.content {
			max-height: 60vh;
			overflow-y: scroll;
		}
		.item {
			display: flex;
			flex-direction: column;
		}
		.product {
			display: flex;
			height: 140upx;
			margin: 10upx 0;
			.image {
				width: 100%;
				max-width: 140upx;
				height: 140upx;
				border-radius: 3px;
				overflow: hidden;
				image{
					width: 100%;
					height: 100%;
					opacity: 1;
				}
			}
			.desc {
				display: flex;
				flex: 1;
				line-height: 150%;
				text-align: left;
				padding: 10upx;
				justify-content: space-between;
				.price {
					color: #fa436a;
					display: flex;
					justify-content: center;
					align-items: center;
				}
			}
		}
		
		.category-content {
			max-height: 40vh;
			overflow-y: scroll;
			.item {
				font-size: 30upx;
				text-align: center;
				margin: 20upx 0;
			}
		}
	}
	
</style>
