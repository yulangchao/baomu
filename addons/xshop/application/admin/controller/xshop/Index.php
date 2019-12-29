<?php

namespace app\admin\controller\xshop;

use app\common\controller\Backend;
use think\Db;
use app\admin\model\xshop\Order as OrderModel;
use think\Config;
use fast\Random;

/**
 * Dashboard
 *
 * @icon fa fa-circle-o
 */
class Index extends Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 控制台
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            return $this->success('');
        }
        $today_start = strtotime(date('Y-m-d'), time());
        // 今日已支付
        $order_paied = OrderModel::where('status', '>', 0)->where('is_pay', 1)->where('create_time', '>', $today_start)->count();
        // 今日未支付
        $order_wait_pay = OrderModel::where('status', 0)->where('create_time', '>', $today_start)->count();
        // 今日已发货
        $order_shipped = OrderModel::where('status', '>', -1)->where('is_delivery', 1)->where('create_time', '>', $today_start)->count();
        // 今日待发货
        $order_wait_ship = OrderModel::where('status', '>', 0)->where('is_pay', 1)->where('is_delivery', 0)->where('create_time', '>', $today_start)->count();
        // 今日收款金额
        $order_money = OrderModel::where('status', '>', 0)->where('is_pay', 1)->where('create_time', '>', $today_start)->sum('order_price');
        $totalInfo = [
            'order_paied' => $order_paied,
            'order_wait_pay' => $order_wait_pay,
            'order_shipped' => $order_shipped,
            'order_wait_ship' => $order_wait_ship,
            'order_money' => $order_money
        ];
        $this->view->assign('totalInfo', $totalInfo);
        return $this->view->fetch();
    }

    /**
     * 选择城市
     */
    public function citySelector()
    {
        if ($this->request->isAjax()) {
            $list = \app\admin\model\xshop\Area::getTreeArray([1, 2]);
            $list = [['id' => 0, 'name' => '全国', 'childlist' => $list]];
            return $this->success('', null, $list);
        }
        return $this->view->fetch();
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        Config::set('default_return_type', 'json');
        $file = $this->request->file('file');
        if (empty($file)) {
            $this->error(__('No file upload or server upload limit exceeded'));
        }

        //判断是否已经存在附件
        $sha1 = $file->hash();
        $extparam = $this->request->post();

        $upload = Config::get('upload');
        
        preg_match('/(\d+)(\w+)/', $upload['maxsize'], $matches);
        $type = strtolower($matches[2]);
        $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
        $size = (int)$upload['maxsize'] * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0);
        $fileInfo = $file->getInfo();
        $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $suffix = $suffix ? $suffix : 'file';

        $mimetypeArr = explode(',', strtolower($upload['mimetype']));
        $typeArr = explode('/', $fileInfo['type']);

        //验证文件后缀
        if ($upload['mimetype'] !== '*' &&
            (
                !in_array($suffix, $mimetypeArr)
                || (stripos($typeArr[0] . '/', $upload['mimetype']) !== false && (!in_array($fileInfo['type'], $mimetypeArr) && !in_array($typeArr[0] . '/*', $mimetypeArr)))
            )
        ) {
            $this->error(__('Uploaded file format is limited'));
        }
        $replaceArr = [
            '{year}'     => date("Y"),
            '{mon}'      => date("m"),
            '{day}'      => date("d"),
            '{hour}'     => date("H"),
            '{min}'      => date("i"),
            '{sec}'      => date("s"),
            '{random}'   => Random::alnum(16),
            '{random32}' => Random::alnum(32),
            '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
            '{suffix}'   => $suffix,
            '{.suffix}'  => $suffix ? '.' . $suffix : '',
            '{filemd5}'  => md5_file($fileInfo['tmp_name']),
        ];
        $savekey = $upload['savekey'];
        $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);

        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName = substr($savekey, strripos($savekey, '/') + 1);
        //
        $splInfo = $file->validate(['size' => $size])->move(ROOT_PATH . '/public' . $uploadDir, $fileName);
        if ($splInfo) {
            $imagewidth = $imageheight = 0;
            if (in_array($suffix, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf', 'apk'])) {
                $imgInfo = getimagesize($splInfo->getPathname());
                $imagewidth = isset($imgInfo[0]) ? $imgInfo[0] : $imagewidth;
                $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : $imageheight;
            }
            $params = array(
                'admin_id'    => (int)$this->auth->id,
                'user_id'     => 0,
                'filesize'    => $fileInfo['size'],
                'imagewidth'  => $imagewidth,
                'imageheight' => $imageheight,
                'imagetype'   => $suffix,
                'imageframes' => 0,
                'mimetype'    => $fileInfo['type'],
                'url'         => $uploadDir . $splInfo->getSaveName(),
                'uploadtime'  => time(),
                'storage'     => 'local',
                'sha1'        => $sha1,
                'extparam'    => json_encode($extparam),
            );
            $attachment = model("attachment");
            $attachment->data(array_filter($params));
            $attachment->save();
            \think\Hook::listen("upload_after", $attachment);
            $this->success(__('Upload successful'), null, [
                'url' => $uploadDir . $splInfo->getSaveName()
            ]);
        } else {
            // 上传失败获取错误信息
            $this->error($file->getError());
        }
    }
}
