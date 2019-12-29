<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;

class AddressModel extends Model
{
    protected $name = 'xshop_address';
    protected $autoWriteTimestamp = true;
    
    protected $hidden = [
        'create_time', 'update_time', 'delete_time'
    ];
    public static function getList()
    {
        $user = UserModel::info();
        return self::where('user_id', $user->id)->select();
    }

    public static function edit($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        if ($is_default) {
            self::where('user_id', $user->id)->update(['is_default' => 0]);
        }
        if (isset($id)) {
            $model = self::where('id', $id)->find();
        } else {
            $model = new self;
        }
        $model->user_id = $user->id;
        $model->address_id = $address_id;
        $model->address = $address;
        $model->street = $street;
        $model->contactor_name = $contactor_name;
        $model->phone = $phone;
        $model->is_default = $is_default;
        return $model->save();
    }

    /**
     * 删除
     */
    public static function del($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        return self::where('user_id', $user->id)->where('id', $address_id)->delete();
    }
}
