<template>
	<view class="content">
		<view class="row b-b">
			<text class="tit">联系人</text>
			<input class="input" type="text" v-model="form.contactor_name" placeholder="收货人姓名" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">手机号</text>
			<input class="input" type="number" v-model="form.phone" placeholder="收货人手机号码" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">地址</text>
			<view class="address-box">
				<area-select v-model="form.address_id" @submit="changeAddress"></area-select>
			</view>
		</view>
		<view class="row b-b">
			<text class="tit">街道</text>
			<input type="text" v-model="form.street" class="input" />
		</view>
		
		<view class="row default-row">
			<text class="tit">设为默认</text>
			<switch :checked="form.is_default == 1" color="#fa436a" @change="switchChange" />
		</view>
		<button class="add-btn" @click="confirm">提交</button>
	</view>
</template>

<script>
	import areaSelect from '@/components/area-select/area-select'
	import { mapActions } from 'vuex'
	
	export default {
		components: {
			areaSelect
		},
		data() {
			return {
				form: {
					contactor_name: '',
					phone: '',
					address_id: null,
					address: '',
					street: '',
					is_default: 0
				}
			}
		},
		onLoad(option){
			let title = '新增收货地址';
			if(option.type==='edit'){
				title = '编辑收货地址'
				this.form = JSON.parse(option.data)
				console.log(this.form)
			}
			this.manageType = option.type;
			uni.setNavigationBarTitle({
				title
			})
			this.getAreas()
		},
		methods: {
			...mapActions(['getUserAddress', 'getAreas']),
			switchChange(e){
				this.form.is_default = e.detail.value ? 1 : 0;
			},
			changeAddress(msg) {
				this.form.address = msg
			},
			chooseAddress() {
				this.$refs.ref.region.show()
			},
			onConfirm(data) {
				console.log(data)
				this.form.address = data.result
			},
			showRegion() {
				this.$refs.region.show()
			},
			//提交
			confirm(){
				let data = this.form;
				if(!data.contactor_name){
					this.$api.msg('请填写收货人姓名');
					return;
				}
				if(!/(^1[3|4|5|7|8][0-9]{9}$)/.test(data.phone)){
					this.$api.msg('请输入正确的手机号码');
					return;
				}
				if(!data.address || !data.address_id){
					this.$api.msg('请在选择所在位置');
					return;
				}
				if(!data.street){
					this.$api.msg('请填写街道信息');
					return;
				}
				this.$http.post('user.address.edit', this.form).then(res => {
					this.$api.msg(`地址${this.manageType=='edit' ? '修改': '添加'}成功`);
					this.getUserAddress()
					setTimeout(() => {
						uni.navigateBack()
					}, 800)
				})
			},
		}
	}
</script>

<style lang="scss">
	page{
		background: $page-color-base;
		padding-top: 16upx;
	}

	.row{
		display: flex;
		align-items: center;
		position: relative;
		padding:0 30upx;
		height: 110upx;
		background: #fff;
		
		.tit{
			flex-shrink: 0;
			width: 120upx;
			font-size: 30upx;
			color: $font-color-dark;
		}
		.address-box {
			flex: 1;
		}
		.input{
			flex: 1;
			font-size: 30upx;
			color: $font-color-dark;
		}
		.icon-shouhuodizhi{
			font-size: 36upx;
			color: $font-color-light;
		}
	}
	.default-row{
		margin-top: 16upx;
		.tit{
			flex: 1;
		}
		switch{
			transform: translateX(16upx) scale(.9);
		}
	}
	.add-btn{
		display: flex;
		align-items: center;
		justify-content: center;
		width: 690upx;
		height: 80upx;
		margin: 60upx auto;
		font-size: $font-lg;
		color: #fff;
		background-color: $base-color;
		border-radius: 10upx;
		box-shadow: 1px 2px 5px rgba(219, 63, 96, 0.4);
	}
</style>
