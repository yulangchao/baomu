<template>
	<uni-popup ref="$unipopup" :mask-click="false" mode="normal" class="t-popup" type="center">
		<view class="container">
			<view class="content">
				<view class="msgbox-content">
					<text>{{title}}</text>
				</view>
				<view class="msgbox-input">
					<input class="input" type="text" v-model="data" />
				</view>
			</view>
			<view class="btns">
				<button class="btn btn-cancel" @click="close">取消</button>
				<button class="btn btn-submit" @click="submit">确定</button>
			</view>
		</view>
		
	</uni-popup>
</template>

<script>
	import { uniPopup } from '@dcloudio/uni-ui'
	export default {
		components: {
			uniPopup
		},
		data() {
			return {
				visible: false,
				data: ''
			}
		},
		props: {
			value: {
				type: Boolean
			},
			title: {
				type: String
			}
		},
		watch: {
			value(val) {
				if (val) this.$refs.$unipopup.open()
				else this.$refs.$unipopup.close()
				this.visible = val
			},
			visible(val) {
				this.$emit('input', val)
			}
		},
		mounted() {
			this.visible = this.value
		},
		methods: {
			submit() {
				let data = this.data
				this.$emit('submit', data)
				this.data = ''
			},
			close() {
				this.visible = false
				this.data = ''
			}
		}
	}
</script>

<style lang="scss">
	.content {
		padding: 0upx 30upx;
		border-radius: 10upx;
		.msgbox-content {
			display: flex;
			justify-content: center;
			margin: 20upx 0;
			color: #404245;
			font-size: 14px;
			line-height: 20px;
		}
		.msgbox-input {
			margin: 30upx 0;
			.input {
				height: 70upx;
				line-height: 70upx;
				border: 1px solid #dedede;
				border-radius: 10upx;
				padding: 10upx;
				width: 100%;
			}
		}
	}
	.btns {
		display: flex;
		height: 80upx;
		line-height: 80upx;
		.btn {
			    line-height: 80upx;
				display: block;
				flex: 1;
				background-color: #fff!important;
				border: none!important;
				margin: 0;
				border: 0;
				&::after {
					border: none;
					border-radius: none;
				}
		}
		
		.btn-cancel {
			font-size: 16px;
			color: #404245;
			border-right: .5px solid #e8eaed;
		}
		.btn-submit {
			font-size: 16px;
			color: #fa800a;
		}
	}
</style>
