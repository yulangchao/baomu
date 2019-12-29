<template>
	<view class="container">
		<view class="left-bottom-sign"></view>
		<view class="back-btn yticon icon-zuojiantou-up" @click="navBack"></view>
		<view class="right-top-sign"></view>
		<!-- 设置白色背景防止软键盘把下部绝对定位元素顶上来盖住输入框等 -->
		<view class="wrapper">
			<view class="left-top-sign">LOGIN</view>
			<view class="welcome">
				欢迎回来！
			</view>
			<view class="input-content">
				<view class="login-way">
					<view class="left nav" :class="{active: !loginWay}" @click="setLoginWay(0)">账号密码登录</view>
					<view class="right nav" :class="{active: loginWay}" @click="setLoginWay(1)">短信验证码登录</view>
				</view>
				<!--账号密码登录-->
				<view class="input-item" v-if="loginWay == 0">
					<text class="tit">账户</text>
					<input type="text" v-model="username" placeholder="请输入用户名/手机号码/邮箱" maxlength="11" data-key="input" />
				</view>
				<view class="input-item" v-if="loginWay == 0">
					<text class="tit">密码</text>
					<input type="mobile" v-model="password" placeholder="8-18位不含特殊字符的数字、字母组合" placeholder-class="input-empty" maxlength="20"
					 password data-key="password"  @confirm="toLogin" />
				</view>

				<!--验证码登录-->
				<view class="input-item" v-if="loginWay == 1">
					<text class="tit">手机号</text>
					<input type="text" v-model="mobile" placeholder="请输入手机号码" maxlength="11" data-key="mobile" />
				</view>
				<view class="input-item" v-if="loginWay == 1">
					<view class="verify-code-selection">
						<text class="tit">验证码</text>
						<text class="btn-get-code" @click="getCode">获取验证码</text>
					</view>

					<input type="number" value="" placeholder="请输入验证码" placeholder-class="input-empty" maxlength="8" password data-key="code"
					 @input="inputChange" @confirm="toLogin" />
				</view>
			</view>
			<button class="confirm-btn" @click="toLogin" :disabled="logining">登录</button>
		</view>
	</view>
</template>

<script>
	import {
		mapMutations,
		mapActions
	} from 'vuex';

	export default {
		data() {
			return {
				username: '',
				password: '',
				logining: false,
				referer: null,
				loginWay: 0,
				mobile: '',
				code: ''
			}
		},
		onLoad(option) {
			this.referer = option.referer ? option.referer : null
		},
		methods: {
			...mapActions(['login', 'getCartProducts']),
			inputChange(e) {
				const key = e.currentTarget.dataset.key;
				this[key] = e.detail.value;
			},
			setLoginWay(val) {
				this.loginWay = val
			},
			getCode() {
				uni.showLoading({
					title: '短信发送中'
				})
				this.$http.post('index.code.get', {mobile: this.mobile}).then(res => {
					uni.hideLoading()
					uni.showToast({
						title: "短信已发送"
					})
				})
			},
			navBack() {
				uni.navigateBack();
			},
			toRegist() {
				uni.navigateTo({
					url: "/pages/public/registe"
				})
			},
			toCenter() {
				uni.switchTab({
					url: '/pages/user/user'
				})
			},
			async toLogin() {
				this.logining = true;
				let sendData = {}
				if (this.loginWay == 0) {
					const { username, password } = this
					sendData = { username, password, loginWay: this.loginWay }
				} else {
					const { mobile, code } = this
					sendData = {
						mobile, code, loginWay: this.loginWay
					}
				}
				this.login(sendData).then(res => {
					this.logining = false
					this.getCartProducts()
					if (this.referer) {
						uni.navigateTo({
							url: decodeURIComponent(this.referer),
							fail: this.toCenter(),
						})
					} else {
						this.toCenter()
					}
				}).catch(e => {
					this.logining = false
				})
			}
		},

	}
</script>

<style lang='scss'>
	page {
		background: #fff;
	}

	.container {
		position: relative;
		width: 100vw;
		height: 100vh;
		overflow: hidden;
		background: #fff;
	}

	.wrapper {
		position: relative;
		z-index: 90;
		background: #fff;
		padding-bottom: 40upx;
	}

	.back-btn {
		position: absolute;
		left: 40upx;
		z-index: 9999;
		padding-top: var(--status-bar-height);
		top: 40upx;
		font-size: 40upx;
		color: $font-color-dark;
	}

	.left-top-sign {
		font-size: 120upx;
		color: $page-color-base;
		position: relative;
		left: -16upx;
	}

	.right-top-sign {
		position: absolute;
		top: 80upx;
		right: -30upx;
		z-index: 95;

		&:before,
		&:after {
			display: block;
			content: "";
			width: 400upx;
			height: 80upx;
			background: #b4f3e2;
		}

		&:before {
			transform: rotate(50deg);
			border-radius: 0 50px 0 0;
		}

		&:after {
			position: absolute;
			right: -198upx;
			top: 0;
			transform: rotate(-50deg);
			border-radius: 50px 0 0 0;
			/* background: pink; */
		}
	}

	.left-bottom-sign {
		position: absolute;
		left: -270upx;
		bottom: -320upx;
		border: 100upx solid #d0d1fd;
		border-radius: 50%;
		padding: 180upx;
	}

	.welcome {
		position: relative;
		left: 50upx;
		top: 50upx;
		font-size: 46upx;
		color: #555;
		text-shadow: 1px 0px 1px rgba(0, 0, 0, .3);
	}

	.input-content {
		padding: 60upx 60upx 0 60upx;
	}

	.input-item {
		display: flex;
		flex-direction: column;
		align-items: flex-start;
		justify-content: center;
		padding: 0 30upx;
		
		background: $page-color-light;
		height: 120upx;
		border-radius: 4px;
		margin-bottom: 50upx;

		&:last-child {
			margin-bottom: 0;
		}

		.tit {
			height: 50upx;
			line-height: 56upx;
			font-size: $font-sm+2upx;
			color: $font-color-base;
		}

		&input {
			height: 60upx;
			font-size: $font-base + 2upx;
			background: $page-color-light;
			color: $font-color-dark;
			width: 100%;
		}
	}

	.confirm-btn {
		height: 76upx;
		line-height: 76upx;
		border-radius: 50px;
		margin-top: 70upx;
		background: $uni-color-primary;
		color: #fff;
		font-size: $font-lg;

		&:after {
			border-radius: 100px;
		}
	}

	.forget-section {
		font-size: $font-sm+2upx;
		color: $font-color-spec;
		text-align: center;
		margin-top: 40upx;
	}

	.register-section {
		position: absolute;
		left: 0;
		bottom: 50upx;
		width: 100%;
		font-size: $font-sm+2upx;
		color: $font-color-base;
		text-align: center;

		text {
			color: $font-color-spec;
			margin-left: 10upx;
		}
	}

	.login-way {
		display: flex;
		flex-direction: row;
		font-size: $font-base;
		justify-content: center;
		margin-bottom: 10upx;

		.left,
		.right {
			margin: 10upx;
		}

		.nav {
			padding: 20upx 20upx;
			border-radius: 40upx;
			border: 1px solid #ccc;
			color: #ccc;
		}

		.active {
			border: 1px solid orangered;
			color: orangered;
		}
	}

	.verify-code-selection {
		display: flex;
		justify-content: space-between;
		width: 100%;

		.btn-get-code {
			display: flex;
			align-items: center;
			font-size: $font-base;
			border: 2upx solid #FF4500;
			color: #FF4500;
			border-radius: 20upx;
			padding: 4upx 20upx;
		}
	}
</style>
