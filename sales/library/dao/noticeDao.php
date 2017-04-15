<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * @公告管理数据模型
 */
class noticeDao extends Dao{

	public $tableName = 'zx_notice';
	
	//获取公告列表，并默认按照ID降序
	//其实应该按照更新时间来逆序
	public function getNoticeList(){
		$sql = sprintf('SELECT * FROM %s ORDER BY `notice_id` DESC',$this->tableName);
		return $this->dao->db->get_all_sql($sql);
	}

	//发布公告
	public function add($data){
 		return $this->dao->db->insert($data, $this->tableName);
	}
}
