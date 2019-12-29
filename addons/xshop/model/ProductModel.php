<?php

namespace addons\xshop\model;

use think\Model;
use app\admin\library\xshop\Tools;
use addons\xshop\exception\NotFoundException;
use think\Hook;

class ProductModel extends Model
{
    protected $name = "xshop_product";
    protected $visible = [];
    protected $hidden = [
        'create_time', 'update_time', 'create_user'
    ];

    protected $append = [
        'service_tags'
    ];

    public static function info($id)
    {
        $user = UserModel::info();
        $product = self::with(['skus', 'unit', 'favorite' => function ($query) use ($user) {
            $user_id = empty($user) ? -1 : $user->id;
            return $query->where('user_id', $user_id);
        }])->find($id);
        if (empty($product)) {
            return new NotFoundException("商品不存在");
        }
        $product = $product->decars();
        $product->favorite = empty($product->favorite) ? 0 : 1;
        $payload = [
            'user' => $user,
            'product' => $product
        ];
        Hook::listen('xshop_product_view', $payload); // 浏览商品
        return $product;
    }

    /**
     * 获取商品列表
     */
    public static function getList($attributes)
    {
        extract($attributes);
        $model = self::with(['skus'])->where('on_sale', 1);
        if (!empty($cat_id)) {
            $model->where(function ($query) use ($cat_id) {
                return $query->where('category_id', 'IN', array_column(CategoryModel::getChildren($cat_id, true), 'id'));
            });
        }
        if (!empty($kw)) {
            $model->where(function ($query) use ($kw) {
                return $query->whereOr('title', 'like', "%$kw%")->whereOr('description', 'like', "%$kw%");
            });
        }
        if (!isset($sort)) {
            $sort = 0;
        }
        $sort = intval($sort);
        switch ($sort) {
            case 0: {
                $model->order('category_recommend', 'DESC')->order('update_time', 'DESC');
            }
            case 1: {
                $model->order('sold_count', 'DESC');
                break;
            }
            case 2: {
                if ($priceOrder == 1) {
                    $model->order('price', 'ASC');
                }
                if ($priceOrder == 2) {
                    $model->order('price', 'DESC');
                }
                break;
            }
        }
        return $model->paginate(10);
    }

    
    /**
     * 获取首页商品列表
     */
    public static function getHomeProducts()
    {
        return ['home_recommend_products' => self::getCategoryRecommend()];
    }

    /**
     * 分类精选
     */
    public static function getCategoryRecommend()
    {
        $navs = NavModel::getList(['nav_type' => [3]])[3] ?? []; // 后台配置的首页分类
        $cat_products = CategoryModel::with(['products' => function ($query) {
            return $query->with(['skus'])->where('on_sale', 1)->order('home_recommend', 'DESC')->limit(10);
        }])->where('id', 'IN', array_column($navs, 'target'))->select();
        $cat_products = collection($cat_products)->toArray();
        $new_cat_products = [];
        foreach ($cat_products as $i => $row) {
            $new_cat_products[$row['id']] = $row;
        }
        foreach ($navs as $i => $nav) {
            if (isset($new_cat_products[$nav->target])) {
                $nav->products = $new_cat_products[$nav->target]['products'];
            } else {
                $nav->products = [];
            }
        }
        return $navs;
    }


    public function skus()
    {
        return $this->hasMany('ProductSkuModel', 'product_id', 'id');
    }
    

    public function favorite()
    {
        return $this->hasOne('FavoriteModel', 'product_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany('ReviewModel', 'product_id', 'id');
    }

    public function unit()
    {
        return $this->hasOne('UnitModel', 'id', 'unit_id');
    }

    public function getImageAttr($value, $data)
    {
        $images = explode(',', $data['image']);
        foreach ($images as $i => $v) {
            $images[$i] = cdnurl($v, true);
        }
        return $images;
    }

    public function getServiceTagsAttr($value, $data)
    {
        return empty($data['service_tags']) ? [] : explode(',', $data['service_tags']);
    }

    public function decars()
    {
        $attrItems = [];
        $attrGroup = [];
        if (!empty($this->skus)) {
            $attrGroup = explode(',', $this->skus[0]['keys']);
            $result = [];
            foreach ($this->skus as $i => $item) {
                $result[] = explode(',', $item->value);
            }
            $attrItems = Tools::reverseDescartes($result);
        }
        $this->attrGroup = $attrGroup;
        $this->attrItems = $attrItems;
        return $this;
    }
}
