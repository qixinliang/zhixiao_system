<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

class recordDao extends Dao{
	public $tableName = 'zx_customer_record';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function addSave($data){
		return $this->dao->db->insert($data,$this->tableName);
	}
}
