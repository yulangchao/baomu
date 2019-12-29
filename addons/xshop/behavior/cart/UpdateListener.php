<?php

namespace addons\xshop\behavior\cart;

use think\Log;

class UpdateListener
{
    public function xshopCartUpdate(&$data)
    {
        Log::info($data->toArray());
    }
}
