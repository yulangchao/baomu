[1]
IF = "SELECT 1 FROM information_schema.columns WHERE TABLE_SCHEMA = '__DATABASE__' AND table_name='__PREFIX__xshop_launch_log' AND COLUMN_NAME='ip';length:0"
THEN = "ALTER TABLE `__PREFIX__xshop_launch_log` ADD ip varchar(45) default '' COMMENT 'Ip';"
[2]
IF = "SELECT 1 FROM information_schema.columns WHERE TABLE_SCHEMA = '__DATABASE__' AND table_name='__PREFIX__xshop_order' AND COLUMN_NAME='remark';length:0"
THEN = "ALTER TABLE `__PREFIX__xshop_order` ADD remark varchar(400) default '' COMMENT '用户备注';"