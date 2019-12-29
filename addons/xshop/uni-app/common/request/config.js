import apis from "./apis";
import tools from '@/common/tools'
export default {
    // 接口主机地址
	baseUrl: 'http://xshop-test.umeng123.com/addons/',
    // 模式，默认NAME。NAME：接口名模式；URI：相对地址模式
    mode: 'NAME',

    // 接口配置
    apis,

    // 请求默认参数
    options: {
        header: {
            'Content-Type': 'application/json',
        },
    },

    // 拦截器
    interceptor: {
        // 请求拦截器，返回false可以阻止请求执行
        request: (options, api) => {
            if (api.auth === 'required') {
                return false;
            }
			options.header['Xshop-Token'] = uni.getStorageSync('token')
            options.header['platform'] = tools.getPlatform()
            return true;
        },

        // 响应拦截器
        response: response => {
			let $msg = false
			// #ifndef MP-ALIPAY
            switch (response.statusCode) {
                case 404:
                    console.error('请求的资源不存在');
					//return Promise.reject('请求的资源不存在')
					$msg = "请求的资源不存在";
                    break;
                case 500:
                    console.error('服务器内部错误');
					$msg = "服务器内部错误"
					// return Promise.reject('服务器内部错误')
                    break;
                default:
                    break;
            }
			// #endif
			if (response.data && response.data.code == 1) return Promise.resolve(response.data)
			if (response.data && response.data.code == 9999) return Promise.reject(response.data)
			$msg = response.data.msg
			uni.showToast({
				title: $msg,
				icon: 'none'
			})
			return Promise.reject(response.data)
        }
    }
};
