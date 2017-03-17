<?php
//客户管理
if (!defined('IS_INITPHP')) exit('Access Denied!');

class customerDao extends Dao{
	//数据库字段.
	//customer_id,客户ID
	//customer_name,客户名
	//investor_id,投资人ID
	//inviter_id,邀请人ID
	//customer_id,客户ID
	public $tableName = 'zx_customer_record';
	public function __construct(){
		parent::__construct();
	}

	public function getCustomers(){
		/*
		$sql = sprintf("SELECT * FROM %s",$this->tableName);
		$ret = $this->dao->db->get_all_sql($sql);
		*/
		$ret = array();
		return $ret;
	}
	
	public function add($salesId){
		//salesId,销售人员ID
		$sql = "SELECT b.id FROM cp_zjingjiren_admin a LEFT JOIN cp_user b ON a.user=b.username WHERE a.id=$salesId";
		$ret = $this->dao->db->get_one_sql($sql);
		if(!isset($ret) || empty($ret)){
			return -1;
		}
		//找到销售人员在cp_user表的ID字段
		$cpUserId = $ret['id'];
		
		//从yaoqingma表里取到客户的ID数据
		/*
		$sql = "SELECT `uid` FROM `cp_user_yaoqingma_list`  WHERE friends=$cpUserId";
		$ret = $this->dao->db->get_all_sql($sql);;
		*/
		
		$cpUserId = 3;
		$sql = "SELECT a.uid,b.username,c.phone,d.UsrName FROM cp_user_yaoqingma_list a LEFT JOIN cp_user b ON b.id=a.uid LEFT JOIN cp_user_info c ON c.uid=b.id LEFT JOIN cp_user_huifu d ON d.uid=b.id WHERE a.friends=$cpUserId";
		var_dump($sql);
		$ret = $this->dao->db->get_all_sql($sql);
		var_dump($ret);
		//组装成大数组
		/*
		$data = array(
		);
		$this->addSave($data);
		*/
	}
	
    public function addSave($data){
        return $this->dao->db->insert($data, $this->tableName);
    }
	
}
