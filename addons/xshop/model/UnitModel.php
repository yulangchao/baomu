<?php

namespace addons\xshop\model;

use think\Model;

class UnitModel extends Model
{
    protected $name = "xshop_unit";
    protected $visible = [
        'name', 'code'
    ];
}
