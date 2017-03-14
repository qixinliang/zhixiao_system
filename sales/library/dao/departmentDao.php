<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*
 * 部门管理模型 by qixinliang 2013.3.13
 */
class departmentDao extends Dao{
    
    public $tableName = 'zx_department';

    public function getdepartmentInfo($id){
        $sql = sprintf("SELECT * FROM %s WHERE department_id=%s ",$this->tableName,$id);
        return $this->dao->db->get_one_sql($sql);
    }

    public function updateDepartmentInfo($id,$data){
        return $this->dao->db->update($id, $data, $this->tableName);
    }

    public function getDepartmentList(){
        $sql = sprintf("SELECT * FROM %s",$this->tableName);
        return  $this->dao->db->get_all_sql($sql);
    }

	public function getDepartmentList2(){
		$sql = sprintf("SELECT * FROM %s",$this->tableName);
		$rows = $this->dao->db->get_all_sql($sql);
		$listRet = array();
		if(isset($rows) && !empty($rows)){
			foreach($rows as $row){
				$listRet[$row['department_id']] = $row;
			}
		}
		return $listRet;
	}
	
	public function getDepartmentTree($pid = 0,&$res = array()){
        $sql = sprintf("SELECT * FROM %s WHERE p_dpt_id=%s",$this->tableName,$pid);
		$ret = $this->dao->db->get_all_sql($sql);
		if(isset($ret) && !empty($ret)){
			foreach($ret as $r){
				$departmentId = $r['department_id'];
				$ret[$r['p_dpt_id']]['son'][$r['department_id']] = &$ret[$r['department_id']];
				//$res[] = $r;
				$this->getDepartmentTree($departmentId,$res);
			}
		}
 		return isset($ret[0]['son']) ? $ret[0]['son'] : array();
	}

    public function addSave($data){
    	return $this->dao->db->insert($data, $this->tableName);
    }

    public function editSave($data){
        return $this->dao->db->update_by_field($data, array('department_id' => $data['department_id']), $this->tableName); //根据条件更新数据
    }

    public function del($id){
       return $this->dao->db->delete_by_field(array('department_id' => $id), $this->tableName);
    }
}
