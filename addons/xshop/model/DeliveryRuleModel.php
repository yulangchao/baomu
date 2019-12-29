<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;

class DeliveryRuleModel extends Model
{
    protected $name = 'xshop_delivery_rule';
    protected $visible = [
    ];
    protected $hidden = [
    ];
    protected $append = [
        'area_ids', 'area_names'
    ];

    
    protected function getAreaIdsAttr($value, $data)
    {
        return explode(',', $data['area_ids']);
    }
    public function getAreaNamesAttr($value, $data)
    {
        return explode(',', $data['area_names']);
    }
}
