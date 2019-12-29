<template>
	<view class="uni-icon" :class="'uni-icon-' + type" :style="{ color: color, 'font-size': size + 'px' }" @click="_onClick">
        <slot></slot>
    </view>
</template>

<script>
	export default {
		name: 'uni-icon',
		props: {
			type: {
				type: String,
				default: ''
			},
			color: {
				type: String,
				default: ''
			},
			size: {
				type: [Number, String],
				default: 16
			}
		},
		methods: {
			_onClick() {
				this.$emit('click');
			}
		}
	};
</script>

<style>
	uni-icon {
		line-height: 1;
	}

	.uni-icon {
		font-family: uniicons;
		font-weight: normal;
		font-style: normal;
		line-height: 1;
		display: inline-block;
		text-decoration: none;
		-webkit-font-smoothing: antialiased;
	}

	.uni-icon.uni-active {
		color: #007aff;
	}
</style>
