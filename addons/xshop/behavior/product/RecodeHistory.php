<?php

namespace addons\xshop\behavior\product;

use think\Log;
use addons\xshop\model\HistoryModel;

class RecodeHistory
{
    public function xshopProductView(&$payload)
    {
        $user = $payload['user'];
        $product = $payload['product'];
        if (!empty($user)) {
            $history = HistoryModel::where('user_id', $user->id)->where('product_id', $product->id)->find();
            if (empty($history)) {
                (new HistoryModel)->save([
                    'user_id' => $user->id,
                    'product_id' => $product->id
                ]);
            } else {
                $history->update_time = time();
                $history->save();
            }
        }
    }
}
