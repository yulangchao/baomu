<?php

namespace addons\xshop;

use app\admin\library\xshop\Tools;
use app\admin\model\xshop\Hook as HookModel;
use app\admin\model\xshop\HookAddon as HookAddonModel;
use think\Db;
use think\Cache;

class Hook
{
    public $hooks = [];
    protected static $instance = null;
    
    /**
    * @param array $options 参数
    * @return Hook
    */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }
        return self::$instance;
    }

    public function add(array $hooks)
    {
        foreach ($hooks as $i => $hook) {
            $hook = is_array($hook) ? $hook : [$hook];
            if (empty($this->hooks[$i])) {
                $this->hooks[$i] = $hook;
            } else {
                $this->hooks[$i] = array_unique(array_merge($this->hooks[$i], $hook));
            }
        }
    }

    public function bind(array $hooks = [])
    {
        $this->add($hooks);
        foreach ($this->hooks as $key => $hook) {
            \think\Hook::add($key, $hook);
        }
    }

    public function importHooks($addonPath)
    {
        $list = $this->getHookData($addonPath);
        $hooks_data = [];
        $hook_addon_data = [];
        foreach ($list as $row) {
            $childList = $row['childList'];
            foreach ($childList as $i => $item) {
                $childList[$i]['hook'] = $row['hook'];
            }
            $hook_addon_data = array_merge($hook_addon_data, $childList);
            unset($row['childList']);
            $hooks_data[] = $row;
        }
        $hook_list = HookModel::where(function ($query) use ($hooks_data) {
            return $query->where('hook', 'IN', array_column($hooks_data, 'hook'));
        })->select();
        $hook_list = collection($hook_list)->toArray();
        if (!empty($hook_list)) {
            foreach ($hooks_data as $i => $row) {
                if (Tools::find_rows($hook_list, ['hook' => $row['hook']]) > -1) {
                    unset($hooks_data[$i]);
                }
            }
        }
        Db::startTrans();
        try {
            HookAddonModel::where('addon_name', $this->addonInfo['name'])->delete();
            $hook = new HookModel;
            $hook->saveAll($hooks_data);
            $hookAddon = new HookAddonModel;
            $hookAddon->saveAll($hook_addon_data);
            Db::commit();
            return true;
        } catch (\think\exception\PDOException $e) {
            Db::rollback();
            throw new \think\Exception($e->getMessage());
        } catch (\think\Exception $e) {
            Db::rollback();
            throw new \think\Exception($e->getMessage());
        }
    }

    private function getHookData($addonPath)
    {
        $content = json_decode(file_get_contents($addonPath . DS . 'xshop-hooks.json'), true);
        $this->addonInfo = parse_ini_file($addonPath . DS . "info.ini");
        $result = [];
        foreach ($content as $row) {
            $result = array_merge($result, $this->parseHook($row['group_title'], $row['list']));
        }
        $result = Tools::rows_merge_same_key($result, 'hook', 'childList');
        return $result;
    }

    private function parseHook($groupName, $hooks)
    {
        $hookList = [];
        $hookListeners = [];
        $result = [];
        foreach ($hooks as $i => $row) {
            $data = [
                'group' => $groupName,
                'hook' => $row['hook'],
                'hook_desc' => $row['description'],
                'payload' => isset($row['payload']) ? $row['payload'] : ''
            ];
            $childList = [];
            foreach ($row['listeners'] as $item) {
                $childList[] = [
                    'class_name' => $item['name'],
                    'class_desc' => $item['description'],
                    'sort' => isset($item['sort']) ? $item['sort'] : 10000,
                    'status' => isset($item['status']) ? $item['status'] : 1,
                    'addon_name' => $this->addonInfo['name'],
                    'addon_title' => $this->addonInfo['title']
                ];
            }
            $data['childList'] = $childList;
            $result[] = $data;
        }
        return $result;
    }

    private function checkSameClass($list)
    {
        $classes = [];
        foreach ($list as $row) {
            foreach ($row['childList'] as $childRow) {
                $index = Tools::find_rows($classes, ['class_name' => $childRow['class_name']]);
                if ($index > -1) {
                    $class = $classes[$index];
                    return "存在相同的执行类：" . $class['class_name'] . " " . $class['addon_name'] . " " . $childRow['addon_name'];
                }
                $classes[] = [
                    'class_name' => $childRow['class_name'],
                    'addon_name' => $childRow['addon_name']
                ];
            }
        }
        return true;
    }

    /**
     * 插件启用
     */
    public function enable($addon_name)
    {
        $addonPath = ADDON_PATH . $addon_name;
        $this->importHooks($addonPath);
        $this->setListenersCache();
    }

    /**
     * 插件禁用
     */
    public function disable($addon_name)
    {
        \addons\xshop\model\HookAddonsModel::disable($addon_name);
        $this->setListenersCache();
    }

    /**
     * 插件卸载
     */
    public function uninstall($addon_name)
    {
        \addons\xshop\model\HookAddonsModel::where('addon_name', $addon_name)->delete();
    }

    /**
     * 缓存行为执行者
     */
    public function setListenersCache()
    {
        $list = HookModel::with(['hookAddon' => function ($query) {
            $query->where('status', 1)->order("sort");
        }])->select();
        $list = collection($list)->toArray();
        $newList = [];
        foreach ($list as $row) {
            if (!empty($row['hook_addon'])) {
                $newList[$row['hook']] = array_column($row['hook_addon'], 'class_name');
            }
        }
        cache('xshop_hooks', $newList);
        return $newList;
    }
}
