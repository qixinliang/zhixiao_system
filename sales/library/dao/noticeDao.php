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

	//根据id获取公告详情
	public function getNoticeInfo($id){
		$sql = sprintf("SELECT * FROM %s WHERE notice_id=%s",$this->tableName,$id);	
		return $this->dao->db->get_one_sql($sql);
	}

	//更新数据
    public function update($data,$id){
        return $this->dao->db->update_by_field($data, array('notice_id' => $id),
			$this->tableName);
    }

	//删除公告
    public function del($id){
       return $this->dao->db->delete_by_field(
		array('notice_id' => $id), $this->tableName);
    }
	
	public function getLatestNotice(){
		$sql = sprintf("SELECT * FROM %s ORDER BY `update_time` DESC LIMIT 1",$this->tableName);
		return $this->dao->db->get_all_sql($sql);
	}
}
