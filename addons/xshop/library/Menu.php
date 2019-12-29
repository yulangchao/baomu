<?php

namespace addonx\xshop\library;

use app\admin\model\AuthRule;
use fast\Tree;
use think\Exception;
use think\exception\PDOException;
use app\common\library\Menu as BaseMenu;

class Menu extends BaseMenu
{
    /**
     * 创建菜单
     * @param array $menu
     * @param mixed $parent 父类的name或pid
     */
    public static function create($menu, $parent = 0)
    {
        if (!is_numeric($parent)) {
            $parentRule = AuthRule::getByName($parent);
            $pid = $parentRule ? $parentRule['id'] : 0;
        } else {
            $pid = $parent;
        }
        $allow = array_flip(['file', 'name', 'title', 'icon', 'condition', 'remark', 'ismenu', 'weigh']);
        foreach ($menu as $k => $v) {
            $hasChild = isset($v['sublist']) && $v['sublist'] ? true : false;

            $data = array_intersect_key($v, $allow);

            $data['ismenu'] = isset($data['ismenu']) ? $data['ismenu'] : ($hasChild ? 1 : 0);
            $data['icon'] = isset($data['icon']) ? $data['icon'] : ($hasChild ? 'fa fa-list' : 'fa fa-circle-o');
            $data['pid'] = $pid;
            $data['status'] = 'normal';
            $data['weigh'] = isset($data['weigh']) ? $data['weigh'] : 0;
            try {
                $menu = AuthRule::create($data);
                if ($hasChild) {
                    self::create($v['sublist'], $menu->id);
                }
            } catch (PDOException $e) {
                throw new Exception($e->getMessage());
            }
        }
    }
}