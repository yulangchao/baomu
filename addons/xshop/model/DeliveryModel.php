<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;

class DeliveryModel extends Model
{
    protected $name = 'xshop_delivery_tpl';
    protected $visible = [
        'id', 'title', 'type'
    ];
    protected $hidden = [
    ];
    protected $append = [
    ];

    public function deliveryRules()
    {
        return $this->hasMany('DeliveryRuleModel', 'tpl_id', 'id');
    }
}
