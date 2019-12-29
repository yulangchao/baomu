<template>
	<view class="">
		<view class="text-normal" @click="pickerOpen">
			<text class="color-tip" v-if="!currentMsg">{{(areas && areas.length) ? msg : '加载中...'}}</text>
			<text v-else>{{currentMsg}}</text>
		</view>
		<uni-popup ref="$unipopup" class="x-picker-ctx" type="bottom">
			<view class="x-picker-hd" @touchmove.stop.prevent catchtouchmove="true">
			  <view class="x-picker-btn" @tap="pickerCancel">取消</view>
			  <view class="x-picker-btn btn-submit" @tap="pickerConfirm">确定</view>
			</view>
			<view class="x-picker-view">
				<picker-view :indicator-style="itemHeight" :value="pickerValue" @change="change">
					<picker-view-column>
						<view class="column"  v-for="(item, index) in provinceList" :key="index">
							{{item.shortname}}
						</view>
					</picker-view-column>
					<picker-view-column>
						<view class="column"  v-for="(item, index) in cityList" :key="index">
							{{item.shortname}}
						</view>
					</picker-view-column>
					<picker-view-column>
						<view class="column"  v-for="(item, index) in areaList" :key="index">
							{{item.shortname}}
						</view>
					</picker-view-column>
				</picker-view>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	import { uniPopup } from '@dcloudio/uni-ui'
	import { mapState, mapActions } from 'vuex'
	export default {
		components: {
			uniPopup
		},
		data() {
			return {
				provinceList: [],
				cityList: [],
				areaList: [],
				pickerValue: [],
				itemHeight:`height: ${uni.upx2px(88)}px;`,
				currentProvince: 0,
				currentCity: 0,
				currentArea: 0,
				currentValue: {},
				currentMsg: null
			}
		},
		computed: {
			...mapState({
				areas: state => state.areas
			}),
		},
		watch: {
			areas(list) {
				this.getProvinceList()
				this.changeProvinceChildren(this.currentProvince)
			},
			currentProvince(val) {
				this.changeProvinceChildren(val)
			},
			currentCity(val) {
				this.changeCityChildren(val)
			},
			value: {
				handler(val) {
					this.currentValue = val
				},
				deep: true
			},
			currentValue: {
				handler(val) {
					this.$emit('input', val)
				},
				deep: true
			}
		},
		props: {
			msg: {
				type: String,
				default: '选择地址'
			},
			value: {
				type: Object | Array
			}
		},
		mounted() {
			let timer = setInterval(() => {
				if (this.areas && this.areas.length) {
					clearInterval(timer)
					this.getProvinceList()
					if (this.value) {
						let item = this.$tools.find_rows(this.areas, {id: this.value}, false)
						this.currentValue = this.value
						if (item) this.currentMsg = item.mergename
					}
					
				}
			}, 500)
		},
		methods: {
			getProvinceList() {
				let result = []
				this.areas.forEach((item, index) => {
					if (item.level == 1) result.push(item)
				})
				this.provinceList = result
				this.changeProvinceChildren(this.currentProvince)
			},
			change(data) {
				this.currentProvince = data.detail.value[0] || 0
				this.currentCity = data.detail.value[1] || 0
				this.currentArea = data.detail.value[2] || 0
				this.pickerValue = [this.currentProvince, this.currentCity, this.currentArea]
			},
			pickerOpen() {
				if (this.areas && this.areas.length) {
					this.$refs.$unipopup.open()
				} else {
					uni.showToast({
						icon: 'none',
						title: '数据加载中，请稍后'
					})
				}
			},
			pickerCancel(e) {
				this.$refs.$unipopup.close()
			},
			pickerConfirm(e) {
				this.$refs.$unipopup.close()
				let item = this.areaList[this.currentArea]
				this.currentValue = item.id
				this.currentMsg = item.mergename
				this.$emit('submit', item.mergename, item, this.pickerValue)
			},
			changeProvinceChildren(val) {
				let result = []
				let province = this.provinceList[val]
				this.areas.forEach((item, index) => {
					if (item.level == 2 && item.pid == province.id) result.push(item)
				})
				this.cityList = result
				this.currentCity = 0
				this.currentArea = 0
				this.changeCityChildren(this.currentCity)
			},
			changeCityChildren(val) {
				let result = []
				let city = this.cityList[val]
				this.areas.forEach((item, index) => {
					if (item.level == 3 && item.pid == city.id) result.push(item)
				})
				this.areaList = result
				this.currentArea = 0
			}
		}
	}
</script>

<style lang="scss">
	.column {
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.color-tip {
		color: $font-color-light;
	}
	.text-normal {
		font-size: $font-base;
	}
	.x-picker-view {
	  width: 100%;
	  height: 476upx;
	  overflow: hidden;
	  background-color: rgba(255, 255, 255, 1);
	  z-index: 666;
	  font-size: $font-base;
	}
	picker-view{
		height: 100%;
	}
	
	.btn-submit {
		color: $base-color;
	}
	
	.x-picker-cxt {
	  position: fixed;
	  bottom: 0;
	  left: 0;
	  width: 100%;
	  transition: all 0.3s ease;
	  transform: translateY(100%);
	  z-index: 3000;
	}
	.x-picker-cnt.show {
	  transform: translateY(0);
	}
	.x-picker-hd {
	  display: flex;
	  align-items: center;
	  padding: 0 30upx;
	  height: 88upx;
	  background-color: #fff;
	  position: relative;
	  text-align: center;
	  font-size: 32upx;
	  justify-content: space-between;
	  .x-picker-btn{
	  	font-size: 30upx;
	  }
	}
	
	.x-picker-hd:after {
	  content: ' ';
	  position: absolute;
	  left: 0;
	  bottom: 0;
	  right: 0;
	  height: 1px;
	  border-bottom: 1px solid #e5e5e5;
	  color: #e5e5e5;
	  transform-origin: 0 100%;
	  transform: scaleY(0.5);
	}
</style>
