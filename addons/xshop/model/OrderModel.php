<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;
use addons\xshop\logic\SkuLogic;
use think\Db;
use think\Hook;

class OrderModel extends Model
{
    protected $name = 'xshop_order';
    protected $autoWriteTimestamp = true;

    /** 订单状态 ↓ */
    public const ORDER_STATUS_NORMAL = 0; // 待付款
    public const ORDER_STATUS_SHIPPED = 1; // 订单已支付 待收货
    public const ORDER_STATUS_RECEIVED = 2; // 已收货
    public const ORDER_STATUS_AFTER_START = 3; // 开始售后流程
    public const ORDER_STATUS_AFTER_DONE = 4; // 售后完成
    public const ORDER_STATUS_DONE = 5; // 订单完成
    public const ORDER_STATUS_CANCEL = -1; // 已取消

    protected $hidden = [
        'create_time', 'update_time', 'delete_time'
    ];
    protected $append = [
        'create_time_text', 'state_tip'
    ];

    public static function getList(array $attributes = [])
    {
        extract($attributes);
        $user = UserModel::info();
        $model = self::with(['products'])->where('user_id', $user->id)->order('create_time', 'DESC');
        $state = empty($state) ? 0 : $state;
        switch ($state) {
            case 1: { // 待付款
                $model->where('status', self::ORDER_STATUS_NORMAL);
                break;
            }
            case 2: { // 待收货
                $model->where('status', self::ORDER_STATUS_SHIPPED);
                break;
            }
            case 3: { // 待评价
                $model->where('buyer_review', 0)->where('status', 'IN', [self::ORDER_STATUS_RECEIVED, self::ORDER_STATUS_AFTER_DONE, self::ORDER_STATUS_DONE]);
                break;
            }
            case 4: { // 售后
                $model->where('status', 'IN', [self::ORDER_STATUS_AFTER_START, self::ORDER_STATUS_AFTER_DONE]);
                break;
            }
            default: {
                break;
            }
        }
        return $model->paginate(10);
    }

    /**
     * @param String order_sn
     */
    public static function info($attributes)
    {
        extract($attributes);
        return self::with(['products'])->where(['order_sn' => $order_sn])->find()->getExpress();
    }

    /**
     * 创建订单
     * @param Integer $address_id require
     * @param Integer $coupon_id
     */
    public static function add($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        
        $list = CartModel::getList(1);
        if (empty($list)) {
            throw new NotFoundException("没有商品");
        }
        $address = AddressModel::find($address_id);
        if (empty($address) || $address->user_id != $user->id) {
            throw new NotFoundException("请选择收货地址");
        }
        foreach ($list as $row) {
            if ($row->product['on_sale'] == 0) throw new Exception("商品 " . $row->product['title'] . " 已下架");
        }
        $skuLogic = SkuLogic::instance()->product($list)
            ->clacRealProductsPrice()   // 计算商品价格
            ->clacDeliveryPrice($address->address_id)       // 计算运费
            ->clacDiscountPrice()           // 计算优惠
            ->clacOrderPrice();      // 计算订单价格
        
        Db::startTrans();
        $order_sn = \addons\xshop\library\Sn::get("O");
        $remark = isset($remark) ? $remark : '';
        try {
            $data = [
                'order_sn' => $order_sn,
                'user_id' => $user->id,
                'contactor' => $address->contactor_name,
                'contactor_phone' => $address->phone,
                'address' => $address->address . ' ' . $address->street,
                'delivery_price' => $skuLogic->delivery_price,
                'products_price' => $skuLogic->products_price,
                'discount_price' => $skuLogic->discount_price,
                'order_price' => $skuLogic->order_price,
                'remark' => $remark
            ];
            $Order = new OrderModel($data);
            $order_id = $Order->save();
            $product_data = [];
            foreach ($skuLogic->products as $i => $row) {
                $item = [
                    'order_id' => $Order->id,
                    'product_id' => $row->product['id'],
                    'sku_id' => $row->sku['id'],
                    'shop_id' => 0,
                    'title' => $row->product['title'],
                    'description' => $row->product['description'],
                    'image' => implode(',', $row->product['image']),
                    'attributes' => $row->sku['value'],
                    'price' => $row->sku['price'],
                    'quantity' => $row->quantity,
                    'product_price' => $row->sku['price'] * $row->quantity,
                    'discount_price' => 0,
                ];
                $product_data[] = $item;
            }
            if (empty($product_data)) {
                Db::rollback();
                throw new Exception("没有选择商品");
            }
            $OrderProduct = new OrderProductModel;
            $OrderProduct->saveAll($product_data, false);
            CartModel::where('user_id', $user->id)->where('is_selected', 1)->delete();
            Db::commit();
            $payload = [
                'order' => $Order,
                'skuLogic' => $skuLogic
            ];
            \think\Hook::listen('xshop_order_create_after', $payload);
        } catch (\think\PDOException $e) {
            Db::rollback();
            throw new Exception($e->getMessage());
        } catch (\think\Exception $e) {
            Db::rollback();
            throw new Exception($e->getMessage());
        }
        return $order_sn;
    }

    /**
     * 立即购买 创建订单
     */
    public static function addOne($attributes)
    {
        $user = UserModel::info();
        $cart = CartModel::add($attributes, true);
        CartModel::updateStatus(['ids' => [$cart->id]]);
        return self::add($attributes);
    }

    /**
     * 获取创建订单的价格
     * @param Integer $address_id require
     * @param Integer $sku_id
     * @param Integer @quantity
     */
    public static function getPrice($attributes)
    {
        extract($attributes);
        if (empty($sku_id)) {
            $products = CartModel::getList(1);
        } else {
            $products = self::buyOneInfo($attributes);
        }
        $address = AddressModel::find($address_id);
        $skuLogic = SkuLogic::instance()->clacPrice($products, $address->address_id);
        return [
            'discount_price' => $skuLogic->discount_price,
            'products_price' => $skuLogic->products_price,
            'delivery_price' => $skuLogic->delivery_price,
            'order_price' => $skuLogic->order_price
        ];
    }
    
    /**
     * 构造立即购买的数据结构
     * @param Integer $sku_id
     * @param Integer $quantity
     */
    public static function buyOneInfo($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        $sku = ProductSkuModel::find($sku_id);
        $product = ProductModel::where('id', $sku->product_id)->find();
        // $delivery = DeliveryModel::find($product->delivery_id);
        $cart = new CartModel;
        $cart->sku = $sku;
        $cart->product = $product;
        // $cart->delivery = $delivery;
        $cart->quantity = intval($quantity);
        $cart->is_selected = 1;
        $list = [$cart];
        // $list = [['sku' => $sku, 'product' => $product, 'quantity' => (int)$quantity, 'is_selected' => 1]];
        
        return $list;
    }

    /**
     * 订单确认收货
     * @param Integer $order_sn 订单sn
     */
    public static function receive($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        $order = self::where('order_sn', $order_sn)->where('user_id', $user->id)->find();
        if (empty($order)) {
            throw new NotFoundException("没有该订单");
        }
        if ($order->status != 1) {
            throw new Exception("订单状态异常，不能收货");
        }
        if ($order->is_pay != 1) {
            throw new Exception("订单未付款");
        }
        if ($order->is_received != 0) {
            throw new Exception("订单已收货");
        }
        $payload = [
            'user' => $user,
            'order' => $order
        ];
        Hook::listen('xshop_order_received_before', $payload); // 订单收货前
        $order->is_received = 1;
        $order->received_time = time();
        $order->status = 2;
        $order->save();
        Hook::listen('xshop_order_received_after', $payload); // 订单收货后
        return true;
    }

    /**
     * 取消订单
     * @param Integer $order_sn 订单sn
     */
    public static function cancel($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        $order = self::where('order_sn', $order_sn)->where('user_id', $user->id)->find();
        if (empty($order)) {
            throw new NotFoundException("没有该订单");
        }
        if ($order->status == -1) {
            throw new Exception("订单已取消");
        }
        if ($order->status != 0) {
            throw new Exception("订单不可取消");
        }
        $payload = [
            'user' => $user,
            'order' => $order
        ];
        Hook::listen('xshop_order_cancel_before', $payload); // 订单取消前
        $order->status = -1;
        $order->save();
        Hook::listen('xshop_order_cancel_after', $payload); // 订单取消后
        return true;
    }
    /**
     * 删除订单
     * @param Integer $order_sn 订单sn
     */
    public static function del($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        $order = self::where('order_sn', $order_sn)->where('user_id', $user->id)->find();
        if (empty($order)) {
            throw new NotFoundException("没有该订单");
        }
        if ($order->status != -1) {
            throw new Exception("请先取消订单");
        }
        $payload = [
            'user' => $user,
            'order' => $order
        ];
        Hook::listen('xshop_order_delete_before', $payload); // 订单删除前
        $order->delete();
        Hook::listen('xshop_order_delete_after', $payload); // 订单删除后
        return true;
    }

    /**
     * 获取快递名称
     */
    public function getExpress()
    {
        $this->express_name = empty($this->express_code) ? '' : ExpressModel::where('code', $this->express_code)->find()->name;
        return $this;
    }
    
    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i", $value) : $value;
    }

    public function getStateTipAttr($value, $data)
    {
        $val = '';
        $status = intval($data['status']);
        switch ($status) {
            case self::ORDER_STATUS_NORMAL: 
                $val = '待付款';
                break;
            case self::ORDER_STATUS_SHIPPED:
                $val = '待收货';
                break;
            case self::ORDER_STATUS_RECEIVED:
                if ($data['buyer_review'] == 1) $val = '已完成';
                else $val = '待评价';
                break;
            case self::ORDER_STATUS_DONE:
                $val = '已完成';
                break;
            case self::ORDER_STATUS_CANCEL:
                $val = '已取消';
                break;
        }
        return $val;
    }

    public function products()
    {
        return $this->hasMany('OrderProductModel', 'order_id', 'id');
    }
}
