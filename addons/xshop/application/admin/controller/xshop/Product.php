<?php

namespace app\admin\controller\xshop;

use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;
use app\admin\model\xshop\ProductSku;
use app\admin\library\xshop\Tools;
use app\admin\model\xshop\Category;
use app\admin\model\xshop\Product as ProductModel;
use app\admin\model\xshop\ServiceTag as ServiceTagModel;
use app\admin\model\xshop\Unit as UnitModel;
use app\admin\model\xshop\Delivery as DeliveryModel;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Product extends Backend
{
    
    /**
     * Product模型对象
     * @var \app\admin\model\xshop\Product
     */
    protected $model = null;
    protected $multiFields = 'home_recommend,category_recommend';
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\xshop\Product;
        $this->modelSceneValidate = true;
        $this->modelValidate = true;
        $unitArr = UnitModel::select();
        $deliveryTpls = DeliveryModel::select();
        $this->assign('unitArr', $unitArr);
        $this->assign('deliveryTpls', $deliveryTpls);
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
        $this->searchFields = ['title', 'description'];
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['category', 'attrs', 'skus'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['category', 'attrs', 'skus'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','title','description','content','image','on_sale','rating','sold_count','review_count','price', 'market_price', 'create_time', 'home_recommend', 'category_recommend', 'service_tags']);
                $row->visible(['category', 'attrs', 'skus']);
                $row->getRelation('category')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $params['create_user'] = $this->auth->id;
                $params['service_tags'] = implode(',', $params['service_tags']);
                $attrs = $this->request->post("attrs/a");
                if (empty(json_decode($attrs['sku']))) {
                    return $this->error("请设置商品规格");
                }
                $rules = [
                    'group' => 'require|array|min:1',
                    'market_price' => 'require|array|min:1',
                    'price' => 'require|array|min:1',
                    'stock' => 'require|array|min:1',
                    'weight' => 'require|array|min:1',
                    'sku' => 'require'
                ];
                $msg = [
                    'group.require' => '请设置商品规格'
                ];
                $result = $this->validate($attrs, $rules, $msg);
                if (true !== $result) {
                    return $this->error($result);
                }
                if (count($attrs['group']) != count(json_decode($attrs['sku'])[0])) {
                    return $this->error('规格子项不可以为空');
                }
                $result = false;
                $db = $this->model->db(false);
                $db->startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
                    $data = ProductSku::createOrUpdate($this->model, $attrs);
                    $db->commit();
                } catch (ValidateException $e) {
                    $db->rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    $db->rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    $db->rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success($data);
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $categoryList = Category::getTreeList();
        $this->assign('categoryList', $categoryList);
        $service_tags = ServiceTagModel::select();
        $this->assign('service_tags', $service_tags);
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->with(['attrs', 'skus'])->find($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $params['service_tags'] = implode(',', $params['service_tags']);
                $attrs = $this->request->post("attrs/a");
                if (empty(json_decode($attrs['sku']))) {
                    return $this->error("请设置商品规格");
                }
                $rules = [
                    'group' => 'require|array|min:1',
                    'market_price' => 'require|array|min:1',
                    'price' => 'require|array|min:1',
                    'stock' => 'require|array|min:1',
                    'weight' => 'require|array|min:1',
                    'sku' => 'require'
                ];
                $msg = [
                    'group.require' => '请设置商品规格'
                ];
                $result = $this->validate($attrs, $rules, $msg);
                if (true !== $result) {
                    return $this->error($result);
                }
                if (count($attrs['group']) != count(json_decode($attrs['sku'])[0])) {
                    return $this->error('规格子项不可以为空');
                }
                $result = false;
                $db = $this->model->db(false);
                $db->startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    $data = ProductSku::createOrUpdate($row, $attrs);
                    $db->commit();
                } catch (ValidateException $e) {
                    $db->rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    $db->rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    $db->rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $attrItems = [];
        if (!empty($row->skus)) {
            $result = [];
            foreach ($row->skus as $i => $item) {
                $result[] = explode(',', $item->value);
            }
            $attrItems = Tools::reverseDescartes($result);
        }
        $categoryList = Category::getTreeList();
        $this->assign('categoryList', $categoryList);

        $this->view->assign('attrItems', collection($attrItems));

        
        $service_tags = ServiceTagModel::select();
        $this->assign('service_tags', $service_tags);

        $this->view->assign("row", $row);
        $this->view->assign('skus', json_encode($row['skus']));
        return $this->view->fetch();
    }

    /**
     * 商品选择窗
     */
    public function select()
    {
        $params = $this->request->get();
        $list = ProductModel::getList($params);
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 显示
     */
    public function show()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        $this->searchFields = ['title', 'description'];
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $ids = $this->request->request('ids');
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['category', 'attrs', 'skus'])
                    ->where($where)
                    ->where(function ($query) use ($ids) {
                        if (!empty($ids)) {
                            $query->where('id', 'IN', $ids);
                        }
                    })
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['category', 'attrs', 'skus'])
                    ->where($where)
                    ->where(function ($query) use ($ids) {
                        if (!empty($ids)) {
                            $query->where('id', 'IN', $ids);
                        }
                    })
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','title','description','content','image','on_sale','rating','sold_count','review_count','price', 'market_price', 'create_time', 'home_recommend', 'category_recommend', 'service_tags']);
                $row->visible(['category', 'attrs', 'skus']);
                $row->getRelation('category')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
}
