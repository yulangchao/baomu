<template>
	<view class="container">
		<view class="carousel">
			<swiper indicator-dots circular=true duration="400">
				<swiper-item class="swiper-item" v-for="(item,index) in product.image" :key="index">
					<view class="image-wrapper">
						<image :src="item" class="loaded" mode="aspectFill"></image>
					</view>
				</swiper-item>
			</swiper>
		</view>

		<view class="introduce-section">
			<text class="title">{{product.title || ''}}</text>
			<view class="price-box">
				<text class="price-tip">￥</text>
				<text class="price">{{currentSku && currentSku.price || 0}}</text>
				<text class="m-price">{{currentSku && currentSku.market_price || 0}}</text>
				<!-- <text class="coupon-tip"></text> -->
			</view>
			<view class="bot-row">
				<text>销量: {{product.sold_count || 0}} {{product.unit && product.unit.code || ''}}</text>
				<text>库存: {{currentSku && currentSku.stock || 0}} {{product.unit && product.unit.code || ''}}</text>
			</view>
		</view>

		<!--  分享 -->
		<!-- <view class="share-section" @click="share">
			<view class="share-icon">
				<text class="yticon icon-xingxing"></text>
				 返
			</view>
			<text class="tit"></text>
			<text class="yticon icon-bangzhu1"></text>
			<view class="share-btn">
				立即分享
				<text class="yticon icon-you"></text>
			</view>
			
		</view>
		 -->
		<view class="c-list">
			<view class="c-row b-b" @click="toggleSpec">
				<text class="tit">购买类型</text>
				<view class="con">
					<text class="selected-text">
						{{(currentSku && currentSku.value) || ''}}
					</text>
				</view>
				<text class="yticon icon-you"></text>
			</view>
			<view class="c-row b-b">
				<text class="tit">数量</text>
				<view class="con quantity-box">
					<number-box v-model="quantity" :min="1" :disabled="true"></number-box>
				</view>
			</view>
			<view class="c-row b-b" @click="couponVisible = 1" v-if="couponList && couponList.length">
				<text class="tit">优惠券</text>
				<text class="con t-r red">领取优惠券</text>
				<text class="yticon icon-you"></text>
			</view>
			<view class="c-row b-b" v-if="product.promotion && product.promotion.length">
				<text class="tit">促销活动</text>
				<view class="con-list">
					<text v-for="(item, i) in product.promotion" :key="i">{{item}}</text>
				</view>
			</view>
			<view class="c-row b-b" v-if="product.service_tags && product.service_tags.length">
				<text class="tit">服务</text>
				<view class="bz-list con">
					<text v-for="(item, index) in product.service_tags" :key="index">{{item}} ·</text>
				</view>
			</view>
		</view>

		<!-- 评价 -->
		<view class="eva-section">
			<view class="e-header">
				<text class="tit">评价</text>
				<text>({{product.review_count || 0}})</text>
				<text class="tip">好评率 {{good_review}}</text>
			</view>
			<view class="eva-box" v-for="(item, index) in reviewList" :key="index">
				<image class="portrait" :src="item.sku.avatar || '/static/missing-face.png'" mode="aspectFill"></image>
				<view class="right">
					<view class="name-box">
						<text class="name">{{item.user.nickname}}</text>
						<text>{{item.star > 3 ? '好评' : (item.star > 2 ? '中评' : '差评')}}</text>
					</view>
					<text class="con">{{item.content || '-'}}</text>
					<view class="bot">
						<text class="attr">购买类型：{{item.sku.attributes}}</text>
						<text class="time">{{item.create_time_text}}</text>
					</view>
				</view>
			</view>
			<view class="loadmore" @click="reviewLoadMore" v-if="showLoadMore">
				加载更多
			</view>
			<view class="loadmore" v-else>
				没有了
			</view>
		</view>

		<view class="detail-desc">
			<view class="d-header">
				<text>图文详情</text>
			</view>
			<rich-text :nodes="product.content"></rich-text>
		</view>

		<!-- 底部操作菜单 -->
		<view class="page-bottom">
			<navigator url="/pages/index/index" open-type="switchTab" class="p-b-btn">
				<text class="yticon icon-xiatubiao--copy"></text>
				<text>首页</text>
			</navigator>
			<navigator url="/pages/cart/cart" open-type="switchTab" class="p-b-btn">
				<text class="yticon icon-gouwuche"></text>
				<text>购物车</text>
			</navigator>
			<view class="p-b-btn" :class="{active: product.favorite}" @click="toFavorite">
				<text class="yticon icon-shoucang"></text>
				<text>收藏</text>
			</view>

			<view class="action-btn-group">
				<button type="primary" class="action-btn no-border buy-now-btn" :class="product.on_sale ? '' : 'disabled'" @click="buy">立即购买</button>
				<button type="primary" class="action-btn no-border add-cart-btn" :class="product.on_sale ? '' : 'disabled'" @click="addCart">加入购物车</button>
			</view>
		</view>


		<!-- 规格-模态层弹窗 -->
		<view class="popup spec" :class="specClass" @touchmove.stop.prevent="stopPrevent" @click="toggleSpec">
			<!-- 遮罩层 -->
			<view class="mask"></view>
			<view class="layer attr-content" @click.stop="stopPrevent">
				<view class="a-t">
					<image :src="product.image && product.image[0]"></image>
					<view class="right">
						<text class="price">{{currentSku && currentSku.price}}</text>
						<text class="stock">库存：{{currentSku && currentSku.stock}} {{product.unit && product.unit.code}}</text>
						<view class="selected">
							已选：
							<text class="selected-text">
								{{currentSku && currentSku.value}}
							</text>
						</view>
					</view>
				</view>
				<view v-for="(item,index) in product.attrItems" :key="index" class="attr-list">
					<text>{{product.attrGroup[index]}}</text>
					<view class="item-list">
						<text v-for="(childItem, childIndex) in item" v-if="childItem.pid === item.id" :key="childIndex" class="tit"
						 :class="{selected: attrChoose[index] === childIndex}" @click="selectSpec(index, childIndex, item)">
							{{childItem}}
						</text>
					</view>
				</view>
				<view class="attr-quantity">
					<text>数量</text>
					<number-box class="number-box" :disabled="true" :step="1" v-model="quantity" :min="1"></number-box>
				</view>
				<button class="btn" @click="toggleSpec">完成</button>
			</view>
		</view>
		<!-- 分享 -->
		<share ref="share" :contentHeight="580" :shareList="shareList"></share>

		<coupon v-model="couponVisible" @pop="couponPop" :list="couponList"></coupon>
	</view>
</template>

<script>
	import share from '@/components/share';
	import coupon from './child/coupon';
	import {
		mapActions,
		mapState
	} from 'vuex'
	import numberBox from '@/components/wlp-number'
	export default {
		components: {
			share,
			numberBox,
			coupon
		},
		data() {
			return {
				id: null,
				product: {},
				quantity: 1,
				attrChoose: {},
				specClass: 'none',
				shareList: [],
				reviewList: [],
				couponList: [],
				couponVisible: 0,
				good_review: '100%',
				showLoadMore: false,
				review_page: 1
			};
		},
		async onLoad(options) {
			this.id = options.id
			this.getProductInfo({
				id: this.id
			}).then(res => {
				if (res.data.on_sale == 0) uni.showToast({
					title: '商品已下架',
					icon: 'none'
				})
				this.product = this.parseProduct(res.data)
				this.specInit()
			});
			this.getReviews()
			if (this.$tools.has_addon('xshopcoupon')) this.getCoupon()
		},
		computed: {
			...mapState({
				user: state => state.user.userinfo
			}),
			currentSku() {
				let attrValues = []
				for (let i in this.attrChoose) {
					attrValues.push(this.product.attrItems[i][this.attrChoose[i]])
				}
				const val = attrValues.join(',')
				for (let i in this.product.skus) {
					if (this.product.skus[i].value == val) return this.product.skus[i]
				}
			}
		},
		methods: {
			...mapActions(['getProductInfo', 'cartAddProduct', 'getCartProducts']),
			//规格弹窗开关
			toggleSpec() {
				if (this.specClass === 'show') {
					this.specClass = 'hide';
					setTimeout(() => {
						this.specClass = 'none';
					}, 250);
				} else if (this.specClass === 'none') {
					this.specClass = 'show';
				}
			},
			specInit() {
				for (let index in this.product.attrGroup) {
					this.$set(this.attrChoose, index, 0)
				}
			},
			//选择规格
			selectSpec(index, childIndex, item) {
				this.$set(this.attrChoose, index, childIndex)
			},
			//分享
			share() {
				this.$refs.share.toggleMask();
			},
			//收藏
			toFavorite() {
				if (!this.user.id) {
					this.goLogin()
					return
				}
				let form = {
					id: this.product.id,
					state: this.product.favorite == 0 ? 1 : 0
				}
				this.$http.post('product.favorite', form).then(res => {
					this.$set(this.product, 'favorite', form.state)
				})
			},
			checkOnsale() {
				if (this.product.on_sale == 0) {
					uni.showToast({
						title: '商品已下架',
						icon: 'none'
					})
					return false
				}
				return true
			},
			buy() {
				if (!this.user.id) {
					this.goLogin()
					return false
				}
				if (!this.checkOnsale()) return;

				if (this.currentSku.stock < this.quantity) {
					uni.showToast({
						title: '库存不足',
						icon: 'none'
					})
					return
				}
				uni.navigateTo({
					url: `/pages/order/createOrder?sku_id=` + this.currentSku.id + '&quantity=' + this.quantity
				})
			},
			addCart() {
				if (!this.user.id) {
					this.goLogin()
					return
				}
				if (!this.checkOnsale()) return;
				if (this.currentSku.stock < this.quantity) {
					uni.showToast({
						title: '库存不足',
						icon: 'none'
					})
					return
				}
				let form = {
					sku_id: this.currentSku.id,
					quantity: this.quantity
				}
				this.cartAddProduct(form).then(res => {
					this.getCartProducts()
					uni.showToast({
						title: '加入成功'
					})
				}).catch(e => {

				})
			},
			stopPrevent() {},
			parseProduct(product) {
				product.content = this.$parseHtml(product.content)
				return product
			},
			goLogin() {
				uni.navigateTo({
					url: '/pages/public/login?referer=' + encodeURIComponent('/pages/product/product?id=' + this.id)
				})
			},
			getCoupon() {
				this.$http.post('product.getCoupons', {
					id: this.id
				}).then(res => {
					this.couponList = res.data
				})
			},
			couponPop(index) {
				this.$set(this.couponList[index], 'has', 1)
			},
			getReviews(type = 'init') {
				this.$http.get('product.reviews', {
					id: this.id,
					page: this.review_page
				}).then(res => {
					this.reviewList = type == 'init' ? res.data.data : this.reviewList.concat(res.data.data)
					this.reviewCount = res.data.total
					this.good_review = res.data.good_review
					this.showLoadMore = res.data.current_page < res.data.last_page ? true : false
				})
			},
			reviewLoadMore() {
				this.review_page += 1
				this.getReviews('loadmore')
			}
		},
		onShareAppMessage() {
			let path = '/pages/product/product?id' + this.product.id
			if (this.user.id) path += '&uid=' + this.user.id
			return {
				title: this.product.title,
				path,
				imageUrl: this.product.image[0]
			}
		}
	}
</script>

<style lang='scss'>
	page {
		background: $page-color-base;
		padding-bottom: 160upx;
	}

	.icon-you {
		font-size: $font-base + 2upx;
		color: #888;
	}

	.carousel {
		height: 722upx;
		position: relative;

		swiper {
			height: 100%;
		}

		.image-wrapper {
			width: 100%;
			height: 100%;
		}

		.swiper-item {
			display: flex;
			justify-content: center;
			align-content: center;
			height: 750upx;
			overflow: hidden;

			image {
				width: 100%;
				height: 100%;
			}
		}

	}

	/* 标题简介 */
	.introduce-section {
		background: #fff;
		padding: 20upx 30upx;

		.title {
			font-size: 32upx;
			color: $font-color-dark;
			height: 50upx;
			line-height: 50upx;
		}

		.price-box {
			display: flex;
			align-items: baseline;
			height: 64upx;
			padding: 10upx 0;
			font-size: 26upx;
			color: $uni-color-primary;
		}

		.price {
			font-size: $font-lg + 2upx;
		}

		.m-price {
			margin: 0 12upx;
			color: $font-color-light;
			text-decoration: line-through;
		}

		.coupon-tip {
			align-items: center;
			padding: 4upx 10upx;
			background: $uni-color-primary;
			font-size: $font-sm;
			color: #fff;
			border-radius: 6upx;
			line-height: 1;
			transform: translateY(-4upx);
		}

		.bot-row {
			display: flex;
			align-items: center;
			height: 50upx;
			font-size: $font-sm;
			color: $font-color-light;

			text {
				flex: 1;
			}
		}
	}

	/* 分享 */
	.share-section {
		display: flex;
		align-items: center;
		color: $font-color-base;
		background: linear-gradient(left, #fdf5f6, #fbebf6);
		padding: 12upx 30upx;

		.share-icon {
			display: flex;
			align-items: center;
			width: 70upx;
			height: 30upx;
			line-height: 1;
			border: 1px solid $uni-color-primary;
			border-radius: 4upx;
			position: relative;
			overflow: hidden;
			font-size: 22upx;
			color: $uni-color-primary;

			&:after {
				content: '';
				width: 50upx;
				height: 50upx;
				border-radius: 50%;
				left: -20upx;
				top: -12upx;
				position: absolute;
				background: $uni-color-primary;
			}
		}

		.icon-xingxing {
			position: relative;
			z-index: 1;
			font-size: 24upx;
			margin-left: 2upx;
			margin-right: 10upx;
			color: #fff;
			line-height: 1;
		}

		.tit {
			font-size: $font-base;
			margin-left: 10upx;
		}

		.icon-bangzhu1 {
			padding: 10upx;
			font-size: 30upx;
			line-height: 1;
		}

		.share-btn {
			flex: 1;
			text-align: right;
			font-size: $font-sm;
			color: $uni-color-primary;
		}

		.icon-you {
			font-size: $font-sm;
			margin-left: 4upx;
			color: $uni-color-primary;
		}
	}

	.c-list {
		font-size: $font-sm + 2upx;
		color: $font-color-base;
		background: #fff;

		.c-row {
			display: flex;
			align-items: center;
			padding: 20upx 30upx;
			position: relative;
		}

		.tit {
			width: 140upx;
		}

		.con {
			flex: 1;
			color: $font-color-dark;

			.selected-text {
				margin-right: 10upx;
			}
		}

		.quantity-box {
			display: flex;
			justify-content: flex-end;
		}

		.bz-list {
			height: 40upx;
			font-size: $font-sm+2upx;
			color: $font-color-dark;

			text {
				display: inline-block;
				margin-right: 30upx;
			}
		}

		.con-list {
			flex: 1;
			display: flex;
			flex-direction: column;
			color: $font-color-dark;
			line-height: 40upx;
		}

		.red {
			color: $uni-color-primary;
		}
	}

	/* 评价 */
	.eva-section {
		display: flex;
		flex-direction: column;
		padding: 20upx 30upx;
		background: #fff;
		margin-top: 16upx;
		.name-box {
			display: flex;
			justify-content: space-between;
		}
		.e-header {
			display: flex;
			align-items: center;
			height: 70upx;
			font-size: $font-sm + 2upx;
			color: $font-color-light;

			.tit {
				font-size: $font-base + 2upx;
				color: $font-color-dark;
				margin-right: 4upx;
			}

			.tip {
				flex: 1;
				text-align: right;
			}

			.icon-you {
				margin-left: 10upx;
			}
		}
		.loadmore {
			display: flex;
			justify-content: center;
			font-size: 24upx;
			padding: 20upx 0;
		}
	}

	.eva-box {
		display: flex;
		padding: 20upx 0;

		.portrait {
			flex-shrink: 0;
			width: 80upx;
			height: 80upx;
			border-radius: 100px;
		}

		.right {
			flex: 1;
			display: flex;
			flex-direction: column;
			font-size: $font-base;
			color: $font-color-base;
			padding-left: 26upx;

			.con {
				font-size: $font-base;
				color: $font-color-dark;
				padding: 20upx 0;
			}

			.bot {
				display: flex;
				justify-content: space-between;
				font-size: $font-sm;
				color: $font-color-light;
			}
		}
	}

	/*  详情 */
	.detail-desc {
		background: #fff;
		margin-top: 16upx;

		.d-header {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 80upx;
			font-size: $font-base + 2upx;
			color: $font-color-dark;
			position: relative;

			text {
				padding: 0 20upx;
				background: #fff;
				position: relative;
				z-index: 1;
			}

			&:after {
				position: absolute;
				left: 50%;
				top: 50%;
				transform: translateX(-50%);
				width: 300upx;
				height: 0;
				content: '';
				border-bottom: 1px solid #ccc;
			}
		}
	}

	/* 规格选择弹窗 */
	.attr-content {
		padding: 10upx 30upx;

		.a-t {
			display: flex;

			image {
				width: 170upx;
				height: 170upx;
				flex-shrink: 0;
				margin-top: -40upx;
				border-radius: 8upx;
				;
			}

			.right {
				display: flex;
				flex-direction: column;
				padding-left: 24upx;
				font-size: $font-sm + 2upx;
				color: $font-color-base;
				line-height: 42upx;

				.price {
					font-size: $font-lg;
					color: $uni-color-primary;
					margin-bottom: 10upx;
				}

				.selected-text {
					margin-right: 10upx;
				}
			}
		}

		.attr-list {
			display: flex;
			flex-direction: column;
			font-size: $font-base + 2upx;
			color: $font-color-base;
			padding-top: 30upx;
			padding-left: 10upx;
		}

		.attr-quantity {
			display: flex;
			flex: 1;
			flex-direction: row;
			font-size: $font-base;
			color: $font-color-base;
			padding-top: 30upx;
			padding-left: 10upx;
			align-items: center;

			.number-box {
				margin-left: 40upx;
			}
		}

		.item-list {
			padding: 20upx 0 0;
			display: flex;
			flex-wrap: wrap;

			text {
				display: flex;
				align-items: center;
				justify-content: center;
				background: #eee;
				margin-right: 20upx;
				margin-bottom: 20upx;
				border-radius: 100upx;
				min-width: 60upx;
				height: 60upx;
				padding: 0 20upx;
				font-size: $font-base;
				color: $font-color-dark;
			}

			.selected {
				background: #fbebee;
				color: $uni-color-primary;
			}
		}
	}

	/*  弹出层 */
	.popup {
		position: fixed;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		z-index: 99;

		&.show {
			display: block;

			.mask {
				animation: showPopup 0.2s linear both;
			}

			.layer {
				animation: showLayer 0.2s linear both;
			}
		}

		&.hide {
			.mask {
				animation: hidePopup 0.2s linear both;
			}

			.layer {
				animation: hideLayer 0.2s linear both;
			}
		}

		&.none {
			display: none;
		}

		.mask {
			position: fixed;
			top: 0;
			width: 100%;
			height: 100%;
			z-index: 1;
			background-color: rgba(0, 0, 0, 0.4);
		}

		.layer {
			position: fixed;
			z-index: 99;
			bottom: 0;
			width: 100%;
			min-height: 40vh;
			border-radius: 10upx 10upx 0 0;
			background-color: #fff;

			.btn {
				height: 66upx;
				line-height: 66upx;
				border-radius: 100upx;
				background: $uni-color-primary;
				font-size: $font-base + 2upx;
				color: #fff;
				margin: 30upx auto 20upx;
			}
		}

		@keyframes showPopup {
			0% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}

		@keyframes hidePopup {
			0% {
				opacity: 1;
			}

			100% {
				opacity: 0;
			}
		}

		@keyframes showLayer {
			0% {
				transform: translateY(120%);
			}

			100% {
				transform: translateY(0%);
			}
		}

		@keyframes hideLayer {
			0% {
				transform: translateY(0);
			}

			100% {
				transform: translateY(120%);
			}
		}
	}

	/* 底部操作菜单 */
	.page-bottom {
		position: fixed;
		left: 30upx;
		bottom: 30upx;
		z-index: 95;
		display: flex;
		justify-content: center;
		align-items: center;
		width: 690upx;
		height: 100upx;
		background: rgba(255, 255, 255, .9);
		box-shadow: 0 0 20upx 0 rgba(0, 0, 0, .5);
		border-radius: 16upx;

		.p-b-btn {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			font-size: $font-sm;
			color: $font-color-base;
			width: 96upx;
			height: 80upx;

			.yticon {
				font-size: 40upx;
				line-height: 48upx;
				color: $font-color-light;
			}

			&.active,
			&.active .yticon {
				color: $uni-color-primary;
			}

			.icon-fenxiang2 {
				font-size: 42upx;
				transform: translateY(-2upx);
			}

			.icon-shoucang {
				font-size: 46upx;
			}
		}

		.action-btn-group {
			display: flex;
			height: 76upx;
			border-radius: 100px;
			overflow: hidden;
			box-shadow: 0 20upx 40upx -16upx #fa436a;
			box-shadow: 1px 2px 5px rgba(219, 63, 96, 0.4);
			background: linear-gradient(to right, #ffac30, #fa436a, #F56C6C);
			margin-left: 20upx;
			position: relative;

			&:after {
				content: '';
				position: absolute;
				top: 50%;
				right: 50%;
				transform: translateY(-50%);
				height: 28upx;
				width: 0;
				border-right: 1px solid rgba(255, 255, 255, .5);
			}

			.action-btn {
				display: flex;
				align-items: center;
				justify-content: center;
				width: 180upx;
				height: 100%;
				font-size: $font-base;
				padding: 0;
				border-radius: 0;
				background: transparent;
			}
		}
	}
</style>
