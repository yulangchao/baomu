<?php

namespace addons\xshop;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Xshop extends Addons
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = [
            [
                "name" => 'xshop',
                "title" => "XShop商城",
                "ismenu" => 1,
                "sublist" => [
                    [
                        "name" => "xshop/index",
                        "title" => "控制台",
                        "ismenu" => 0,
                        "weigh" => 5,
                        "sublist" => [
                            [
                                "name" => "xshop/index/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/index/cityselector",
                                "title" => "选择城市"
                            ],
                            [
                                "name" => "xshop/index/upload",
                                "title" => "上传文件"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/nav",
                        "title" => "导航管理",
                        "ismenu" => 1,
                        "weigh" => 10,
                        "sublist" => [
                            [
                                "name" => "xshop/nav/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/nav/add",
                                "title" => "添加"
                            ],
                            [
                                "name" => "xshop/nav/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name" => "xshop/nav/del",
                                "title" => "删除"
                            ],
                            [
                                "name" => "xshop/nav/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/category",
                        "title" => "商品分类管理",
                        "ismenu" => 1,
                        "weigh" => 15,
                        "sublist" => [
                            [
                                "name" => "xshop/category/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/category/add",
                                "title" => "添加"
                            ],
                            [
                                "name" => "xshop/category/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name" => "xshop/category/del",
                                "title" => "删除"
                            ],
                            [
                                "name" => "xshop/category/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/product",
                        "title" => "商品管理",
                        "ismenu" => 1,
                        "weigh" => 20,
                        "sublist" => [
                            [
                                "name" => "xshop/product/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/product/add",
                                "title" => "添加"
                            ],
                            [
                                "name" => "xshop/product/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name" => "xshop/product/del",
                                "title" => "删除"
                            ],
                            [
                                "name" => "xshop/product/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/order",
                        "title" => "订单管理",
                        "ismenu" => 1,
                        "weigh" => 25,
                        "sublist" => [
                            [
                                "name" => "xshop/order/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/order/add",
                                "title" => "添加"
                            ],
                            [
                                "name" => "xshop/order/pay",
                                "title" => "付款"
                            ],
                            [
                                "name" => "xshop/order/ship",
                                "title" => "发货"
                            ],
                            [
                                "name" => "xshop/order/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name" => "xshop/order/del",
                                "title" => "删除"
                            ],
                            [
                                "name" => "xshop/order/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/unit",
                        "title" => "记量单位管理",
                        "ismenu" => 1,
                        "weigh" => 30,
                        "sublist" => [
                            [
                                "name" => "xshop/unit/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/unit/add",
                                "title" => "添加"
                            ],
                            [
                                "name" => "xshop/unit/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name" => "xshop/unit/del",
                                "title" => "删除"
                            ],
                            [
                                "name" => "xshop/unit/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/delivery",
                        "title" => "运费模板管理",
                        "ismenu" => 1,
                        "weigh" => 35,
                        "sublist" => [
                            [
                                "name" => "xshop/delivery/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/delivery/add",
                                "title" => "添加"
                            ],
                            [
                                "name" => "xshop/delivery/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name" => "xshop/delivery/del",
                                "title" => "删除"
                            ],
                            [
                                "name" => "xshop/delivery/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/express",
                        "title" => "快递公司管理",
                        "ismenu" => 1,
                        "weigh" => 40,
                        "sublist" => [
                            [
                                "name" => "xshop/express/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/express/getAll",
                                "title" => "查询所有快递公司"
                            ],
                            [
                                "name" => "xshop/express/add",
                                "title" => "添加"
                            ],
                            [
                                "name" => "xshop/express/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name" => "xshop/express/del",
                                "title" => "删除"
                            ],
                            [
                                "name" => "xshop/express/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/service_tag",
                        "title" => "服务标签管理",
                        "ismenu" => 1,
                        "weigh" => 45,
                        "sublist" => [
                            [
                                "name" => "xshop/service_tag/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/service_tag/add",
                                "title" => "添加"
                            ],
                            [
                                "name" => "xshop/service_tag/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name" => "xshop/service_tag/del",
                                "title" => "删除"
                            ],
                            [
                                "name" => "xshop/service_tag/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/hook",
                        "title" => "钩子管理",
                        "ismenu" => 1,
                        "weigh" => 50,
                        "sublist" => [
                            [
                                "name" => "xshop/hook/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/hook/addons",
                                "title" => "查看行为"
                            ],
                            [
                                "name" => "xshop/hook/reload",
                                "title" => "重载钩子"
                            ],
                            [
                                "name" => "xshop/hook/del",
                                "title" => "删除"
                            ],
                            [
                                "name" => "xshop/hook/multi",
                                "title" => "行为开启/关闭"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/launch_log",
                        "title" => "应用启动日志",
                        "ismenu" => 1,
                        "weigh" => 55,
                        "sublist" => [
                            [
                                "name" => "xshop/launch_log/index",
                                "title" => "查看"
                            ]
                        ]
                    ],
                    [
                        "name" => "xshop/app_update",
                        "title" => "APP版本更新",
                        "ismenu" => 1,
                        "weigh" => 60,
                        "sublist" => [
                            [
                                "name" => "xshop/app_update/index",
                                "title" => "查看"
                            ],
                            [
                                "name" => "xshop/app_update/add",
                                "title" => "新增"
                            ],
                            [
                                "name" => "xshop/app_update/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name" => "xshop/app_update/del",
                                "title" => "删除"
                            ],
                            [
                                "name" => "xshop/app_update/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        Menu::create($this->menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete("xshop");
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        try {
            \addons\xshop\Sql::instance()->exec('xshop');
            \addons\xshop\Hook::instance()->enable('xshop');
            Menu::enable('xshop');
            return true;
        } catch (\think\Exception $e) {
            throw new \think\Exception($e->getMessage());
        }
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable("xshop");
        return true;
    }
}
