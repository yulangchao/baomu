<?php

namespace addons\xshop\controller;

use addons\xshop\model\ProductModel;
use addons\xshop\model\ProductSkuModel;
use addons\xshop\model\FavoriteModel;
use addons\xshop\validate\ProductValidate;
use addons\xshop\model\ReviewModel;
use think\Db;

/**
 * 商品
 * @ApiWeigh (5)
 */
class Product extends Base
{
    protected $needLogin = ['favorite'];
    /**
     * 获取商品信息
     * @ApiParams (name="id", type="integer", required=true, description="商品id")
     */
    public function index()
    {
        return $this->success('', ProductModel::info((int)input('id')));
    }
    /**
     * 获取商品评价信息
     * @ApiParams (name="id", type="integer", required=true, description="商品id")
     */
    public function getReviews()
    {
        $params = $this->request->get();
        $result = $this->validate($params, ProductValidate::class . '.id');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', ReviewModel::getList($params));
    }

    public function getList()
    {
        $params = $this->request->get();
        return $this->success('', ProductModel::getList($params));
    }

    /**
     * 获取首页推荐商品
     */
    public function getHomeProducts()
    {
        return $this->success('', ProductModel::getHomeProducts());
    }

    /** 收藏/取消收藏商品 */
    public function favorite()
    {
        $params = $this->request->post();
        $result = $this->validate($params, ProductValidate::class . '.favorite');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', FavoriteModel::add($params));
    }
}
