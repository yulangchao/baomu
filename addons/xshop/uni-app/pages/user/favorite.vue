<template>
	<view class="content">
		<view class="list">
			<empty v-if="list && list.length==0"></empty>
			<view class="item" v-for="(item, index) in list" :key="index" @click="toDetail(item)">
				<view class="image-box">
					<image class="image" :src="item.product.image[0]"></image>
				</view>
				<view class="text">
					<text class="title">{{item.product.title}}</text>
					<text class="price">￥{{item.product.price}}</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import empty from "@/components/empty";
	export default {
		components: {
			empty
		},
		data() {
			return {
				list: []
			}
		},
		onLoad() {
			this.getList()
		},
		methods: {
			getList() {
				return this.$http.get('user.favorite').then(res => {
					this.list = res.data
				}).catch(e => {})
			},
			toDetail(item) {
				uni.navigateTo({
					url: '/pages/product/product?id=' + item.product.id
				})
			}
		},
		onPullDownRefresh() {
			this.getList().then(res => {
				uni.stopPullDownRefresh()
			}).catch(e => {
				uni.stopPullDownRefresh()
			})
		}
	}
</script>

<style lang="scss">
	.list {
		display: flex;
		flex-direction: column;
		padding: 0 10upx;
		.item {
			display: flex;
			overflow: hidden;
			margin: 10upx 0;
			.image-box {
				display:  flex;
				height: 200upx;
				width: 200upx;
				.image {
					width: 100%;
					height: 100%;
				}
			}
			.text {
				font-size: $font-base;
				padding: 10px;
				display: flex;
				flex-direction: column;
				flex: 1;
				.title {
					display: -webkit-box;
					-webkit-box-orient: vertical;
					-webkit-line-clamp: 2;
					overflow: hidden;
				}
				.price {
					color: red;
					font-size: $font-base;
					margin: 10upx 0;
				}
			}
		}
	}
</style>
