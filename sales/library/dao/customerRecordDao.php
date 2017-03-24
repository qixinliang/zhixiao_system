<?php
/*
 *@分配历史纪录表
 */
if (!defined('IS_INITPHP')) exit('Access Denied!');
class customerRecordDao extends Dao{
	public $tableName = 'zx_customer_record';
	
	public function __construct(){
		parent::__construct();
	}

	public function addSave($data){
		return $this->dao->db->insert($data,$this->tableName);
	}

    public function getRecords($page,$offset,$where){
        $sql = sprintf("SELECT * FROM %s where 1=1 $where limit $page,$offset",$this->tableName);       
        $ret = $this->dao->db->get_all_sql($sql);
        return $ret;
    }
    public function getRecordsCount($where){
        $sql = "SELECT count(record_id) AS count FROM $this->tableName where 1=1 $where";
        $ret = $this->dao->db->get_one_sql($sql);
        return $ret;
    }
}
