<?php

namespace addons\xshop\behavior\cart;

use think\Log;

class AddListener
{
    public function xshopCartAdd(&$data)
    {
        Log::info($data->toArray());
    }
}
