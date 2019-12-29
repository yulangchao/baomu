<?php

namespace app\admin\controller\xshop;

use app\common\controller\Backend;
use app\admin\model\xshop\Category as CategoryModel;
use fast\Tree;
/**
 * 商品分类管理
 *
 * @icon fa fa-circle-o
 */
class Category extends Backend
{
    
    /**
     * Category模型对象
     * @var \app\admin\model\xshop\Category
     */
    protected $model = null;
    protected $parent_id = 'parent_id';
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\xshop\Category;
        $this->categoryList = CategoryModel::getTreeList();
        array_unshift($this->categoryList, ['id' => 0, 'name' => '无']);
        $this->assign('categoryList', $this->categoryList);
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
        $this->relationSearch = false;
        $this->searchFields = 'name';
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
            
            $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit(1000)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id', 'parent_id','name','sort', 'image', 'create_time']);
            }
            $list = collection($list)->toArray();
            $tree = Tree::instance();
            $tree->init($list, 'parent_id');
            $list = $tree->getTreeList($tree->getTreeArray(0));
            $result = array("rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 选择分类
     */
    public function select() {
        return $this->view->fetch();
    }
}
