<?php

namespace app\admin\controller\xshop;

use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;
use app\admin\model\xshop\Delivery as DeliveryModel;
/**
 * 运费模板管理
 *
 * @icon fa fa-circle-o
 */
class Delivery extends Backend
{
    
    /**
     * Delivery模型对象
     * @var \app\admin\model\xshop\Delivery
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\xshop\Delivery;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

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
                $list = $this->request->post("list/a");
                if (empty($list)) return $this->error("请添加运费规则");
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
                    $data = \app\admin\model\xshop\DeliveryRule::add($this->model->id, $list);
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
                    $this->success('', null, $data);
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->with(['deliveryRules'])->find($ids);
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
                $params = $this->preExcludeFields($params);$list = $this->request->post("list/a");
                if (empty($list)) return $this->error("请添加运费规则");
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
                    \app\admin\model\xshop\DeliveryRule::where('tpl_id', $ids)->delete();
                    $data = \app\admin\model\xshop\DeliveryRule::add($ids, $list);
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
        
        
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();
                }
                \app\admin\model\xshop\DeliveryRule::where('tpl_id', 'IN', $ids)->delete();
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /** 设置默认运费模板 */
    public function setDefault() {
        $id = $this->request->param('ids');
        DeliveryModel::where('id', 'not in', $id)->update(['is_default' => 0]);
        DeliveryModel::where('id', $id)->update(['is_default' => 1]);
        return $this->success();
    }

}
