<?php

namespace addons\xshop;

use \think\Db;
use \think\Exception;

class Sql
{
    public static function instance()
    {
        return new self;
    }

    public function exec($addon = 'xshop')
    {
        $dirPath = ADDON_PATH . DS . $addon . DS . 'sql';
        $files = scandir($dirPath);
        $lock = 'install.lock';
        if (in_array($lock, $files)) {
            return false;
        }
        foreach ($files as $v) {
            if (!in_array($v, ['.', '..', $lock])) {
                $arr = parse_ini_file($dirPath . DS . $v, true);
                if (!empty($arr)) {
                    foreach ($arr as $item) {
                        if (!empty($item['IF'])) {
                            try {
                                $_arr = explode(';', $item['IF']);
                                if (empty($_arr)) {
                                    throw new Exception("格式错误");
                                }
                                $sql = $_arr[0];
                                $data = Db::getPdo()->query($this->parseSql($sql))->fetch();
                                $data = ['data' => $data];
                                $rule = isset($_arr[1]) ? $_arr[1] : '';
                                $rule = ['data' => $rule];
                                if (empty($item['THEN'])) {
                                    throw new Exception("格式错误");
                                }
                                $validate = new \think\Validate($rule);
                                if ($validate->check($data)) {
                                    Db::getPdo()->exec($this->parseSql($item['THEN']));
                                }
                            } catch (\PdoException $e) {
                                throw new Exception($e->getMessage());
                            } catch (Exception $e) {
                                throw $e;
                            }
                        }
                    }
                } else {
                    $sql = $this->parseSql(file_get_contents($dirPath . DS . $v));
                    try {
                        Db::getPdo()->exec($sql);
                    } catch (\PdoException $e) {
                        throw Exception($e->getMessage());
                    }
                }
            }
        }
        $file = fopen($dirPath . DS . $lock, 'w');
        fclose($file);
        return true;
    }

    public function parseSql($sql)
    {
        $parseArr = [
            '__PREFIX__' => config('database.prefix'),
            '__DATABASE__' => config('database.database')
        ];
        foreach ($parseArr as $k => $v) {
            $sql = str_replace($k, $v, $sql);
        }
        return $sql;
    }
}
