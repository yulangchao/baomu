<template>
	<view class="content b-t">
		<view class="list b-b" v-for="(item, index) in address" :key="index" @click="checkAddress(item)">
			<view class="wrapper">
				<view class="address-box">
					<text v-if="item.is_default == 1" class="tag">默认</text>
					<text class="address">{{item.address}} {{item.street}}</text>
				</view>
				<view class="u-box">
					<text class="name">{{item.contactor_name}}</text>
					<text class="mobile">{{item.phone}}</text>
				</view>
			</view>
			<text class="yticon icon-bianji" @click.stop="addAddress('edit', item)"></text>
			<text class="del-btn yticon icon-iconfontshanchu1" @click.stop="delAddress(item)"></text>
		</view>
		
		<empty v-if="address.length == 0"></empty>
		
		<button class="add-btn" v-on:click="addAddress('add')">新增地址</button>
	</view>
</template>

<script>
	import { mapState, mapActions } from 'vuex'
	import empty from '@/components/empty.vue'
	export default {
		components: { empty },
		data() {
			return {
				source: 0,
				addressList: [],
				option: {}
			}
		},
		onLoad(option){
			this.source = option.source;
			this.option = option
			this.getUserAddress()
		},
		computed: {
			...mapState({
				address: state => state.user.address
			})
		},
		methods: {
			...mapActions(['getUserAddress']),
			checkAddress(item){
				if(this.source == 1){
					this.option.address_id = item.id
					uni.navigateTo({
						url: '/pages/order/createOrder?' + this.$tools.queryStringify(this.option)
					})
				}
			},
			addAddress(type, item){
				uni.navigateTo({
					url: `/pages/address/addressManage?type=${type}&data=${JSON.stringify(item)}`
				})
			},
			delAddress(item) {
				uni.showModal({
					title: '提示',
					content: '确定删除地址吗？',
					success: res => {
						if (res.confirm) {
							this.$http.post('user.address.del', {address_id: item.id}).then(res => {
								this.getUserAddress()
							})
						}
					}
				})
			},
			refreshList(data, type){
				this.addressList.unshift(data);
			}
		}
	}
</script>

<style lang='scss'>
	page{
		padding-bottom: 120upx;
	}
	.content{
		position: relative;
	}
	.list{
		display: flex;
		align-items: center;
		padding: 20upx 30upx;;
		background: #fff;
		position: relative;
	}
	.wrapper{
		display: flex;
		flex-direction: column;
		flex: 1;
	}
	.address-box{
		display: flex;
		align-items: center;
		.tag{
			font-size: 24upx;
			color: $base-color;
			margin-right: 10upx;
			background: #fffafb;
			border: 1px solid #ffb4c7;
			border-radius: 4upx;
			padding: 4upx 10upx;
			line-height: 1;
		}
		.address{
			font-size: $font-base;
			color: $font-color-dark;
			flex: 1;
		}
	}
	.u-box{
		font-size: 28upx;
		color: $font-color-light;
		margin-top: 16upx;
		.name{
			margin-right: 30upx;
		}
	}
	.icon-iconfontshanchu1{
		display: flex;
		align-items: center;
		height: 80upx;
		font-size: 40upx;
		color: $font-color-light;
		padding-left: 30upx;
	}
	
	.add-btn{
		position: fixed;
		left: 30upx;
		right: 30upx;
		bottom: 16upx;
		z-index: 95;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 690upx;
		height: 80upx;
		font-size: 32upx;
		color: #fff;
		background-color: $base-color;
		border-radius: 10upx;
		box-shadow: 1px 2px 5px rgba(219, 63, 96, 0.4);		
	}
	.del-btn {
		color:#fa436a;
	}
</style>
