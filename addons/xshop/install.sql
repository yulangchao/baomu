
CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `address_id` int(10) unsigned DEFAULT NULL,
  `address` varchar(255) NOT NULL COMMENT '地址',
  `street` varchar(255) NOT NULL COMMENT '街道地址',
  `contactor_name` varchar(100) NOT NULL COMMENT '联系人',
  `phone` varchar(50) NOT NULL COMMENT '联系电话',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `delete_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户地址表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_advance_pay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) NOT NULL COMMENT '内部订单号',
  `order_sn_re` varchar(50) NOT NULL COMMENT '外部订单号',
  `platform` varchar(20) NOT NULL COMMENT '支付平台',
  `pay_method` varchar(20) NOT NULL COMMENT '支付方式',
  `create_time` int(10) DEFAULT NULL COMMENT '支付时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='预支付表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_app_update` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `description` varchar(255) NOT NULL COMMENT '更新描述',
  `platform` varchar(50) NOT NULL COMMENT '平台',
  `source_file` varchar(500) DEFAULT NULL COMMENT '资源文件',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) NOT NULL,
  `delete_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='APP版本更新表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '商品',
  `sku_id` int(10) unsigned DEFAULT NULL COMMENT 'SKU',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '销售价',
  `quantity` int(10) DEFAULT '0' COMMENT '数量',
  `is_selected` tinyint(1) DEFAULT '1' COMMENT '是否选中',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='购物车表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `image` varchar(500) DEFAULT NULL COMMENT '图标',
  `sort` int(10) NOT NULL DEFAULT '1000' COMMENT '排序',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_delivery_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_id` int(10) unsigned NOT NULL COMMENT '模板ID',
  `first_price` decimal(10,2) NOT NULL COMMENT '首重/件价格',
  `rest_price` decimal(10,2) NOT NULL COMMENT '续重/件价格',
  `area_ids` varchar(500) DEFAULT NULL,
  `area_names` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='运费规则表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_delivery_rule_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_area_id` int(10) unsigned NOT NULL COMMENT '运费规则ID',
  `area_id` int(10) unsigned NOT NULL COMMENT '地区',
  `area_name` varchar(100) NOT NULL COMMENT '地区名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='运费规则地区对照表';



CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_delivery_tpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '计费方式',
  `sort` int(5) unsigned NOT NULL DEFAULT '1000' COMMENT '排序',
  `is_default` tinyint(1) DEFAULT '0' COMMENT '默认',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='运费模板表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_express` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL COMMENT '快递公司编号',
  `name` varchar(100) NOT NULL COMMENT '快递公司名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='快递公司表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_favorite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '商品',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订评价表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '商品',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='浏览历史表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_hook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(50) NOT NULL COMMENT '分组',
  `hook` varchar(50) NOT NULL COMMENT '钩子名称',
  `hook_desc` varchar(255) NOT NULL COMMENT '钩子描述',
  `group_sort` int(5) DEFAULT '1' COMMENT '分组排序',
  `hook_sort` int(5) DEFAULT '1' COMMENT '钩子排序',
  `payload` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='钩子表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_hook_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_name` varchar(50) NOT NULL COMMENT '执行类',
  `class_desc` varchar(255) NOT NULL COMMENT '执行描述',
  `addon_name` varchar(255) DEFAULT '1' COMMENT '插件名称',
  `sort` int(5) DEFAULT '1' COMMENT '排序',
  `hook` varchar(255) DEFAULT NULL,
  `addon_title` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='钩子绑定插件表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_launch_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `platform` varchar(20) NOT NULL COMMENT '来源',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `systeminfo` text COMMENT '系统信息',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `delete_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='应用启动日志表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `description` varchar(500) NOT NULL COMMENT '描述',
  `nav_type` tinyint(1) DEFAULT '0' COMMENT '导航分类',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '跳转类型',
  `target` varchar(500) DEFAULT NULL COMMENT '跳转目标',
  `params` varchar(500) DEFAULT NULL COMMENT '参数',
  `sort` int(5) unsigned DEFAULT '10000' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `image` varchar(500) DEFAULT NULL COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='导航表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) NOT NULL COMMENT '订单编号',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `is_pay` tinyint(1) DEFAULT '0' COMMENT '是否已支付',
  `pay_time` int(10) unsigned DEFAULT NULL COMMENT '支付时间',
  `is_delivery` tinyint(1) DEFAULT '0' COMMENT '是否发货',
  `delivery` int(10) unsigned DEFAULT NULL COMMENT '发货时间',
  `is_received` tinyint(1) DEFAULT '0',
  `received_time` int(10) DEFAULT NULL,
  `express_code` varchar(45) DEFAULT NULL COMMENT '快递公司编号',
  `express_no` varchar(45) DEFAULT NULL COMMENT '快递单号',
  `status` tinyint(1) DEFAULT '0' COMMENT '订单状态',
  `contactor` varchar(45) NOT NULL COMMENT '联系人',
  `contactor_phone` varchar(45) NOT NULL COMMENT '联系电话',
  `address` varchar(500) DEFAULT NULL COMMENT '送货地址',
  `delivery_price` decimal(10,2) DEFAULT '0.00' COMMENT '运费',
  `order_price` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  `pay_price` decimal(10,2) DEFAULT '0.00' COMMENT '应付金额',
  `pay_type` varchar(15) DEFAULT NULL COMMENT '支付平台',
  `pay_method` varchar(15) DEFAULT NULL COMMENT '支付方式',
  `products_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品总额',
  `discount_price` decimal(10,2) DEFAULT '0.00' COMMENT '优惠金额',
  `payed_price` decimal(10,2) DEFAULT '0.00' COMMENT '已付金额',
  `buyer_review` tinyint(1) DEFAULT '0' COMMENT '买家是否评价',
  `saler_review` tinyint(1) DEFAULT '0' COMMENT '卖家是否评价',
  `saler_remark` varchar(255) DEFAULT NULL COMMENT '买家备注',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '最后更新',
  `delete_time` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_sn_UNIQUE` (`order_sn`),
  KEY `order_sn` (`order_sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_order_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned DEFAULT NULL COMMENT '订单',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '商品',
  `sku_id` int(10) unsigned DEFAULT NULL,
  `shop_id` int(10) unsigned DEFAULT NULL COMMENT '商家',
  `title` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `description` varchar(255) DEFAULT NULL COMMENT '商品描述',
  `image` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `attributes` varchar(255) DEFAULT NULL COMMENT '商品规格',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '销售价',
  `quantity` int(10) DEFAULT '0' COMMENT '数量',
  `product_price` decimal(10,2) DEFAULT '0.00' COMMENT '应付金额',
  `discount_price` decimal(10,2) DEFAULT '0.00' COMMENT '优惠金额',
  `order_price` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  `buyer_review` tinyint(1) DEFAULT '0',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `delete_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单商品表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned DEFAULT NULL COMMENT '分类',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `image` varchar(255) NOT NULL COMMENT '图片',
  `on_sale` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否销售',
  `rating` double(8,2) NOT NULL DEFAULT '5.00' COMMENT '折扣',
  `sold_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售数量',
  `unit_id` int(10) DEFAULT NULL COMMENT '单位',
  `review_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评价数量',
  `service_tags` varchar(500) DEFAULT NULL,
  `home_recommend` tinyint(1) DEFAULT '0' COMMENT '首页推荐',
  `category_recommend` tinyint(1) DEFAULT '0' COMMENT '分类推荐',
  `price` decimal(10,2) NOT NULL COMMENT '销售价',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='商品表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_product_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) DEFAULT NULL,
  `sku_id` int(10) unsigned NOT NULL COMMENT '商品',
  `key` varchar(50) DEFAULT NULL COMMENT '属性名称',
  `value` varchar(50) DEFAULT NULL COMMENT '属性值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='商品属性表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_product_sku` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL COMMENT '商品',
  `keys` varchar(255) DEFAULT NULL COMMENT '属性',
  `value` text COMMENT '属性值',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售价',
  `stock` int(10) NOT NULL DEFAULT '0' COMMENT '库存',
  `weight` float NOT NULL DEFAULT '0' COMMENT '重量',
  `sn` varchar(50) NOT NULL DEFAULT '' COMMENT '货号',
  `market_price` decimal(10,2) DEFAULT '0.00' COMMENT '市场价',
  `sold_count` int(10) DEFAULT '0' COMMENT '销量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='商品属性表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_review` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `order_id` int(10) unsigned DEFAULT NULL COMMENT '订单',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '商品',
  `sku_id` int(10) unsigned DEFAULT NULL COMMENT 'SKU',
  `content` text COMMENT '评价内容',
  `star` tinyint(1) DEFAULT '5' COMMENT '星级',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '最后更新',
  `delete_time` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='订评价表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_service_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '名称',
  `description` varchar(255) NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='服务标签表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '单位名称',
  `code` varchar(50) NOT NULL COMMENT '单位符号',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='计量单位表';

CREATE TABLE IF NOT EXISTS `__PREFIX__xshop_vendor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vendor` varchar(50) NOT NULL COMMENT '第三方',
  `unionid` varchar(50) NOT NULL COMMENT '平台唯一标识',
  `openid` varchar(50) NOT NULL COMMENT '应用唯一标识',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '绑定用户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='第三方账户表';

ALTER TABLE `__PREFIX__xshop_hook_addons` 
CHANGE COLUMN `class_name` `class_name` VARCHAR(255) NOT NULL COMMENT '执行类' ;

INSERT INTO `__PREFIX__config` (`name`, `group`,`title`, `type`,`value`,`content`) 
SELECT 'xshop_h5_appid', 'basic', '公众号APPID', 'string', '', '' FROM DUAL
WHERE NOT EXISTS 
(SELECT * FROM `__PREFIX__config` WHERE name='xshop_h5_appid');

INSERT INTO `__PREFIX__config` (`name`, `group`,`title`, `type`,`value`,`content`) 
SELECT 'xshop_h5_AppSecret', 'basic', '公众号AppSecret', 'string', '', '' FROM DUAL
WHERE NOT EXISTS 
(SELECT * FROM `__PREFIX__config` WHERE name='xshop_h5_AppSecret');

INSERT INTO `__PREFIX__config` (`name`, `group`,`title`, `type`,`value`,`content`) 
SELECT 'xshop_wx_mp_appid', 'basic', '微信小程序APPID', 'string', '', '' FROM DUAL
WHERE NOT EXISTS 
(SELECT * FROM `__PREFIX__config` WHERE name='xshop_wx_mp_appid');

INSERT INTO `__PREFIX__config` (`name`, `group`,`title`, `type`,`value`,`content`) 
SELECT 'xshop_wx_mp_AppSecret', 'basic', '微信小程序AppSecret', 'string', '', '' FROM DUAL
WHERE NOT EXISTS 
(SELECT * FROM `__PREFIX__config` WHERE name='xshop_wx_mp_AppSecret');

INSERT INTO `__PREFIX__config` (`name`, `group`,`title`, `type`,`value`,`content`) 
SELECT 'xshop_tt_mp_appid', 'basic', '头条小程序APPID', 'string', '', '' FROM DUAL
WHERE NOT EXISTS 
(SELECT * FROM `__PREFIX__config` WHERE name='xshop_tt_mp_appid');

INSERT INTO `__PREFIX__config` (`name`, `group`,`title`, `type`,`value`,`content`) 
SELECT 'xshop_tt_mp_AppSecret', 'basic', '头条小程序AppSecret', 'string', '', '' FROM DUAL
WHERE NOT EXISTS 
(SELECT * FROM `__PREFIX__config` WHERE name='xshop_tt_mp_AppSecret');

INSERT INTO `__PREFIX__config` (`name`, `group`,`title`, `type`,`value`,`content`) 
SELECT 'xshop_tt_mp_mchid', 'basic', '头条小程序商户ID', 'string', '', '' FROM DUAL
WHERE NOT EXISTS 
(SELECT * FROM `__PREFIX__config` WHERE name='xshop_tt_mp_mchid');

INSERT INTO `__PREFIX__config` (`name`, `group`,`title`, `type`,`value`,`content`) 
SELECT 'xshop_tt_mp_app_id', 'basic', '头条小程序支付app_id', 'string', '', '' FROM DUAL
WHERE NOT EXISTS 
(SELECT * FROM `__PREFIX__config` WHERE name='xshop_tt_mp_app_id');

INSERT INTO `__PREFIX__config` (`name`, `group`,`title`, `type`,`value`,`content`) 
SELECT 'xshop_tt_mp_pay_secret', 'basic', '头条小程序支付Secret', 'string', '', '' FROM DUAL
WHERE NOT EXISTS 
(SELECT * FROM `__PREFIX__config` WHERE name='xshop_tt_mp_pay_secret');

