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

CREATE TABLE `zhixiao`.`zx_customer_pool` ( 
	`customer_pool_id` INT(11) UNSIGNED NOT NULL COMMENT '主键' , 
	`investor_id` INT(11) UNSIGNED NOT NULL COMMENT '客户/投资人ID' ,
	`investor_login_name` VARCHAR(32) NOT NULL COMMENT '客户登陆线上系统的用户名' ,
	`investor_real_name` VARCHAR(32) NOT NULL COMMENT '客户真实名字' ,
	`investor_cellphone` INT(11) NOT NULL COMMENT '客户手机号' ,
	`inviter_id` INT(11) NOT NULL COMMENT '邀请人员/销售ID' ,
	`inviter_name` VARCHAR(32) NOT NULL COMMENT '邀请人名字' ,
	`inviter_department_id` INT(11) NOT NULL COMMENT '邀请人所属部门' ,
	`inviter_role_id` INT(11) NOT NULL COMMENT '邀请人职级',
	`inviter_off_time` INT(11) NOT NULL COMMENT '邀请人离职日期',
	`invest_status` TINYINT(3) NOT NULL COMMENT '投资状态' ,
	`create_time` INT(11) NOT NULL COMMENT '创建日期' ,
	`update_time` INT(11) NOT NULL COMMENT '更新日期' ,
	INDEX `idx_investor_id` (`investor_id`)
)ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci COMMENT = '公共客户池表';
ALTER TABLE `zx_customer_pool`
  ADD PRIMARY KEY (`customer_pool_id`),
  ADD KEY `idx_investor_id` (`investor_id`);
ALTER TABLE `zx_customer_pool`
  ADD PRIMARY KEY (`customer_pool_id`),
  ADD KEY `idx_investor_id` (`investor_id`);

CREATE TABLE `zx_customer_record` (
  `record_id` int(11) UNSIGNED NOT NULL COMMENT '主键',
  `inverstor_id` int(11) NOT NULL COMMENT '客户/投资人ID',
  `origin_inviter_id` int(11) NOT NULL COMMENT '客户分配前的邀请人/销售人员ID',
  `new_inviter_id` int(11) NOT NULL COMMENT '客户分配后的邀请人/销售ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户分配历史纪录表';

ALTER TABLE `zx_customer_record`
  ADD PRIMARY KEY (`record_id`);

ALTER TABLE `zx_customer_record`
  MODIFY `record_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键';
