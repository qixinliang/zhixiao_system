DROP TABLE IF EXISTS `zx_department`;  
CREATE TABLE `zx_department` (
`department_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`p_dpt_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '上级部门ID',
`leaer_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '部门负责人ID',
`deparment_name` varchar(32) NOT NULL DEFAULT '' COMMENT '部门名称', 
`status` tinyint(3) NOT NULL DEFAULT 0 COMMENT '部门状态 0 正常 -1 撤销',
`create_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
`update_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
PRIMARY KEY (`department_id`),
KEY `idx_department_name` (`department_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='部门管理表';

ALTER TABLE `cp_zjingjiren_admin_group` DROP COLUMN `privilege`, ADD COLUMN `privilege` int(11) COMMENT '角色权限' AFTER `keep`;
