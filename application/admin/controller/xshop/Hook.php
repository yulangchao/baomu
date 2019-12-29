<?php

namespace app\admin\controller\xshop;

use app\common\controller\Backend;
use app\admin\library\xshop\FileUtil;
use app\admin\library\xshop\Tools;
use app\admin\model\xshop\Hook as HookModel;
use app\admin\model\xshop\HookAddon as HookAddonModel;
use think\Db;
/**
 * 钩子管理
 *
 * @icon fa fa-circle-o
 */
class Hook extends Backend
{
    
    /**
     * Hook模型对象
     * @var \app\admin\model\xshop\Hook
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\xshop\Hook;

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
            $total = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['hookAddon'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id', 'group','hook','hook_desc', 'status', 'hook_addon', 'payload']);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $addons = HookAddonModel::where(true)->group("addon_name")->field(["addon_name", "addon_title"])->select();
        $this->view->assign('addons', $addons);
        return $this->view->fetch();
    }

    /**
     * 查看行为
     */
    public function addons()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        $this->searchFields = 'name';
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $model = new HookAddonModel;
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            $hook_name = $this->request->request('hook');
            
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $where = [
                'hook' => $hook_name
            ];
            $total = $model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * 重载钩子
     */
    public function reload() {
        try {
            $addon_name = $this->request->post('addon_name');
            if (empty($addon_name)) return $this->error("请选择插件");
            $result = \addons\xshop\Hook::instance()->importHooks(ADDON_PATH . DS . $addon_name);
            \addons\xshop\Hook::instance()->setListenersCache();
            return $this->success();
        } catch(\think\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 行为开启/关闭
     */
    public function multi($ids = "")
    {
        $ids = $ids ? $ids : $this->request->param("ids");
        $model = new HookAddonModel;
        if ($ids) {
            if ($this->request->has('params')) {
                parse_str($this->request->post("params"), $values);
                $values = array_intersect_key($values, array_flip(is_array($this->multiFields) ? $this->multiFields : explode(',', $this->multiFields)));
                if ($values || $this->auth->isSuperAdmin()) {
                    $adminIds = $this->getDataLimitAdminIds();
                    if (is_array($adminIds)) {
                        $model->where($this->dataLimitField, 'in', $adminIds);
                    }
                    $count = 0;
                    Db::startTrans();
                    try {
                        $list = $model->where($model->getPk(), 'in', $ids)->select();
                        foreach ($list as $index => $item) {
                            $count += $item->allowField(true)->isUpdate(true)->save($values);
                        }
                        Db::commit();
                    } catch (PDOException $e) {
                        Db::rollback();
                        $this->error($e->getMessage());
                    } catch (Exception $e) {
                        Db::rollback();
                        $this->error($e->getMessage());
                    }
                    if ($count) {
                        \addons\xshop\Hook::instance()->setListenersCache();
                        $this->success();
                    } else {
                        $this->error(__('No rows were updated'));
                    }
                } else {
                    $this->error(__('You have no permission'));
                }
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }
}
