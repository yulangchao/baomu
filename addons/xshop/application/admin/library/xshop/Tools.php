<?php
namespace app\admin\library\xshop;

class Tools {
    /**
     * @param Array $rows 
     * @param Array $search ['id' => 1, 'name' => 'ali']
     */
    public static function find_rows(Array $rows, Array $search) {
        foreach ($rows as $i => $row) {
            $result = true;
            foreach ($search as $k => $v) {
                if (isset($row[$k]) && $row[$k] !== $v) {
                    $result = false;
                    break 1;
                }
            }
            if ($result) return $i;
        }
        return -1;
    }

    /**
     * 笛卡尔积还原
     */
    public static function reverseDescartes(Array $rows) {
        if (!empty($rows)) {
            $colCount = count($rows[0]);
            $collection = collection($rows);
            $result = [];
            while ($colCount > 0) {
                $colCount --;
                $col = $collection->column($colCount);
                $item = [];
                foreach($col as $v) {
                    if (!in_array($v, $item)) $item[] = $v;
                }
                array_unshift($result, $item);
            }
            return $result;
        }
        return $rows;
    }

    public static function array_integer(Array $array) { 
        foreach ($array as $i => $v) {
            $array[$i] = intval($v);
        }
        return $array;
    }

    /**
     * 合并数据集相同的key
     */
    public static function rows_merge_same_key($rows, $key, $merge_key) {
        $new_rows = [];
        foreach ($rows as $row) {
            $index = self::find_rows($new_rows, [$key => $row[$key]]);
            if ($index > -1) {
                $new_rows[$index][$merge_key] = array_merge($new_rows[$index][$merge_key], $row[$merge_key]);
            } else $new_rows[] = $row;
        }
        return $new_rows;
    }

    /**
     * 数据集分组
     */
    public static function groupBy($rows, $keys, $symbol = '___') {
        $result = [];
        foreach ($rows as $i => $row) {
            $new_key = [];
            foreach ($keys as $k) {
                $new_key[] = $row[$k];
            }
            $new_key = implode($symbol, $new_key);
            $result[$new_key][] = $row;
        }
        return $result;
    }
}