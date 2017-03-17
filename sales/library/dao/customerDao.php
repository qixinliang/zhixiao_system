<?php
//客户管理
if (!defined('IS_INITPHP')) exit('Access Denied!');

class customerDao extends Dao{
	public $tableName = 'zx_customer_pool';
	public function __construct(){
		parent::__construct();
	}

	public function getCustomers($where = 0){
		/*
		$sql = sprintf("SELECT * FROM %s",$this->tableName);
		$ret = $this->dao->db->get_all_sql($sql);
		*/
		$ret = array(
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
		);
		return $ret;
	}
	
	public function add($adminId){
		//adminDao...
		$adminDao = InitPHP::getDao('admin');
		//找到销售人员在cp_user表的ID字段
		$cpUserId = $adminDao->GetToZiXiTongUserId($adminId); 
		$cpUserId = 3;
	
		//根据该销售人员获取一下他的(客户/投资人)信息
		$yaoqingDao = InitPHP::getDao('user_yaoqingma_listDao');
		$investorList = $yaoqingDao->getInvestorList($cpUserId);
		if(!isset($investorList) || empty($investorList)){
			return -1;
		}
		$fData = array();
		foreach($inverstorList as $k => $v){
			$data = array();
			$infoEx = $adminDao->adminInfoEx($adminId);
			if(!isset($infoEx) || empty($infoEx)){
				continue;
			}
			$departmentId = $infoEx['department_id'];
			$roleId = $infoEx['gid'];
			$data['investor_id'] = $v['uid'];
			$data['investor_login_name'] = $v['username'];
			$data['investor_real_name'] = $v['UsrName'];
			$data['investor_cellphone'] = $v['phone'];
			$data['inviter_id'] = $cpUserId;
			$data['inviter_name'] = $infoEx['UsrName']; 
			$data['inviter_department_id'] = $infoEx['department_id'];
			$data['inviter_role_id'] = $infoEx['gid'];
			$data['inviter_off_time'] = time();
			$data['invest_status'] = 0;
			$data['create_time'] = time();
			$data['update_time'] = time();
			$this->addSave($data);
			$fData[] = $data;
		}
		return $fData;
	}
	
    public function addSave($data){
        return $this->dao->db->insert($data, $this->tableName);
    }
	
}
