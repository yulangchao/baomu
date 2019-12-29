<template>
	<view class="container">
		<view class="list-cell b-b m-t" @click="navTo('修改昵称', 'nickname')" hover-class="cell-hover" :hover-stay-time="50">
			<text class="cell-tit">修改昵称</text>
			<text class="cell-more yticon icon-you"></text>
		</view>
		<view class="list-cell b-b" @click="navTo('修改密码', 'password')" hover-class="cell-hover" :hover-stay-time="50">
			<text class="cell-tit">修改密码</text>
			<text class="cell-more yticon icon-you"></text>
		</view>
		
		<prompt :title="title" @submit="submit" v-model="popShow"></prompt>
	</view>
	
</template>

<script>
	import {  
	    mapMutations, mapActions
	} from 'vuex';
	import prompt from './child/prompt'
	export default {
		components: {
			prompt
		},
		data() {
			return {
				popShow: false,
				title: '',
				key: ''
			};
		},
		methods:{
			...mapMutations(['SAVE_USERINFO']),
			navTo(title, key){
				this.title = title
				this.key = key
				this.popShow = true
			},
			submit(val) {
				let form = {}
				form[this.key] = val
				this.$http.post('user.info.edit', form).then(res => {
					this.popShow = false
					uni.showToast({
						title: '修改成功'
					})
					this.SAVE_USERINFO(res.data)
				})
			}
		}
	}
</script>

<style lang='scss'>
	page{
		background: $page-color-base;
	}
	.list-cell{
		display:flex;
		align-items:baseline;
		padding: 20upx $page-row-spacing;
		line-height:60upx;
		position:relative;
		background: #fff;
		justify-content: center;
		&.log-out-btn{
			margin-top: 40upx;
			.cell-tit{
				color: $uni-color-primary;
				text-align: center;
				margin-right: 0;
			}
		}
		&.cell-hover{
			background:#fafafa;
		}
		&.b-b:after{
			left: 30upx;
		}
		&.m-t{
			margin-top: 16upx; 
		}
		.cell-more{
			align-self: baseline;
			font-size:$font-lg;
			color:$font-color-light;
			margin-left:10upx;
		}
		.cell-tit{
			flex: 1;
			font-size: $font-base + 2upx;
			color: $font-color-dark;
			margin-right:10upx;
		}
		.cell-tip{
			font-size: $font-base;
			color: $font-color-light;
		}
		switch{
			transform: translateX(16upx) scale(.84);
		}
	}
</style>
