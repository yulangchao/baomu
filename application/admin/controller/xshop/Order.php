<?php

namespace app\admin\controller\xshop;

use app\common\controller\Backend;


/**
 * 订单管理
 *
 * @icon fa fa-circle-o
 */
class Order extends Backend
{
    
    /**
     * Order模型对象
     * @var \app\admin\model\xshop\Order
     */
    protected $model = null;
    protected $searchFields = ['order_sn', 'contactor', 'contactor_phone'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\xshop\Order;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['user', 'express'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','order_sn','is_pay','pay_time','is_delivery','delivery','status','contactor','contactor_phone','delivery_price','order_price','pay_price','discount_price','payed_price', 'create_time','update_time', 'express_no', 'products_price', 'address', 'pay_type', 'pay_method', 'remark']);
                $row->visible(['user']);
                $row->getRelation('user')->visible(['username','nickname']);
                $row->visible(['express']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 付款
     */
    public function pay() {
        $id = $this->request->post('id');
        try {
            $this->success('', null, $this->model->pay($id));
        } catch(\think\Exception $e) {
            $this->error($e->getMessage());
        }
        
    }

    /**
     * 发货
     */
    public function ship() {
        $param = $this->request->post();
        $result = $this->validate($param, [
            'id' => 'require',
            'express_code|快递公司编号' => 'require',
            'express_no|快递单号' => 'require'
        ]);
        if (true !== $result) {
            return $this->error($result);
        }
        try {
            return $this->success('', null, $this->model->ship($param));
        } catch (\think\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
