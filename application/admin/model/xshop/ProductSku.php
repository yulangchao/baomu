<?php

namespace app\admin\model\xshop;

use think\Model;
use app\admin\library\xshop\Tools;
use app\admin\validate\xshop\ProductSku as ProductSkuValidate;
use think\exception\ValidateException;
use think\Validate;

class ProductSku extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'xshop_product_sku';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
    ];

    public static function getList($params) {
        extract($params);
        $model = new self;
        $kw = isset($kw) ? $kw : null;
        $model->with(['product'])->where(function($query) use($kw) {
            $product = new Product;
            if ($kw != null) $product->where('title', 'like', "%$kw%");
            return $query->where('product_id', 'IN', $product->column('id'));
        });
        return $model->paginate(10);
    }

    /**
     * 创建或更新SKU
     */
    public static function createOrUpdate(Product $product, $attrs) {
        $groups = $attrs['group'];
        $skus = json_decode($attrs['sku'], true);
        foreach ($groups as $v) {
            if (false !== strpos($v, ',')) throw new ValidateException("规格 $v 不可包含符号‘,’");
        }
        
        foreach ($skus as $item) {
            foreach ($item as $v) {
                if (false !== strpos($v, ',')) throw new ValidateException("规格子项 $v 不可包含符号‘,’");
            }
        }
        $market_prices = $attrs['market_price'];
        $prices = $attrs['price'];
        $stocks = $attrs['stock'];
        $weights = $attrs['weight'];
        $sns = $attrs['sn'];
        $old_skus = $product->skus;
        $delete_ids = [];
        $result = [
            'delete' => [],
            'insert' => [],
            'update' => []
        ];
        /** 删除旧数据 ↓ */
        $new_skus = [];
        foreach ($skus as $i => $sku) {
            $new_skus[] = [
                'keys' => implode(',', $groups),
                'value' => implode(',', $sku)
            ];
        }
        foreach ($old_skus as $i => $sku) {
            if (Tools::find_rows($new_skus, ['keys' => $sku->keys, 'value' => $sku->value]) == -1) {
                $delete_ids[] = $sku->id;
                unset($old_skus[$i]);
            }
        }
        $result['delete'] = $delete_ids;
        /** 删除旧数据 ↑ */
        $list = [];
        $lower_price = -1;
        foreach ($skus as $i => $sku) {
            $keys = implode(',', $groups);
            $data = [
                'product_id' => $product->id,
                'keys' => $keys,
                'value' => implode(',', $sku),
                'market_price' => $market_prices[$i],
                'price' => $prices[$i],
                'stock' => $stocks[$i],
                'weight' => $weights[$i],
                'sn' => $sns[$i]
            ];
            $rule = [
                'product_id|商品' => 'require|number',
                'keys' => 'require',
                'value' => 'require',
                'market_price|市场价' => 'require|number|>:0',
                'price|销售价' => 'require|number|>:0',
                'stock|库存' => 'require|integer|>:0',
                'weight|重量' => 'require|number|>:0'
            ];
            $validate = new Validate($rule);
            if (!$validate->check($data)) {
                throw new ValidateException($validate->getError());
            }
            $min = min($lower_price, $prices[$i]);
            $lower_price = $min >= 0 ? $min : max($lower_price, $prices[$i]);
            $sku_str = implode(',', $sku);
            
            $index = Tools::find_rows($old_skus, ['keys' => $keys, 'value' => $sku_str, 'product_id' => $product->id]);
            if ($index > -1) {
                $list[] = array_merge($data, ['id' => $old_skus[$index]->id]);
            } else {
                $list[] = $data;
            }
        }
        
        // 删除旧数据
        self::where('id', 'IN', $delete_ids)->delete();
        $ProductSku = new ProductSku;
        if (!empty($list)) $result['update'] = $ProductSku->saveAll($list);
        $product->price = $lower_price;
        $product->save();
        if ($result['update'] === false) throw new \think\PDOException($ProductSku->getError());
        return $result;
    }

    public function product() {
        return $this->belongsTo('Product', 'product_id', 'id', [], 'LEFT');
    }
}
