<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*
 * 部门管理模型 by qixinliang 2013.3.13
 */
class departmentDao extends Dao{
	const DEPARTMENT_STATUS_NORMAL = 0;
	const DEPARTMENT_STATUS_ERROR = 1;
    private $arrTree = array();
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

	//根据父节点添加子节点
	public function addNodes($pid,$name){
		//先检查父节点存在不存在
		$sql = sprintf("SELECT `department_id` FROM %s WHERE department_id=%s",$this->tableName,$pid);
		$ret = $this->dao->db->get_one_sql($sql);
		if(!isset($ret) || empty($ret)){
			return -1;
		}

		$data = array(
			'p_dpt_id' 			=> $pid,
			'department_name' 	=> $name,
			'status' 			=> self::DEPARTMENT_STATUS_NORMAL,
			'create_time' 		=> time(),
			'update_time' 		=> time()
		);
		return $this->addSave($data);
	}
	//合并节点。。。
	//删除节点。。。
	public function deleteNodes($id){
		$sql = sprintf("SELECT `department_id` FROM %s WHERE p_dpt_id=%s",$this->tableName,$id);
		$ret = $this->dao->db->get_all_sql($sql);
		if(isset($ret) && !empty($ret)){
			//还有子节点，请勿删除。
			return -1;
		}
       return $this->dao->db->delete_by_field(array('department_id' => $id), $this->tableName);
	}
    
	public function getParentNodeById($id){
		$sql = sprintf("SELECT `p_dpt_id` FROM %s WHERE department_id=%s",$this->tableName,$id);
		$ret = $this->dao->db->get_one_sql($sql);
		if(!isset($ret) || empty($ret)){
			return -1;	
		}
		$pid = $ret['p_dpt_id'];
		$sql = sprintf("SELECT * FROM %s WHERE department_id=%s",$this->tableName,$pid);
		$ret = $this->dao->db->get_one_sql($sql);
		return $ret;
	}

	public function getChilds($id){
		$lists = $this->getDepartmentList2();
		$arrTree = $this->getMenuTree($lists,$id);
		$this->arrTree = array();
		return $arrTree;
		
	}
	
	public function getMenuTree($arrOrigin,$pid,$level=0){
    	if(empty($arrOrigin)) return FALSE;
    	$level++;
    	foreach($arrOrigin as $k => $v){
        	if($v['p_dpt_id' ] == $pid){
            	$v['level'] = $level;
            	$this->arrTree[] = $v;
            	unset($arrOrigin[$k]); //注销当前节点数据，减少已无用的遍历
            	$this->getMenuTree($arrOrigin, $v['department_id'], $level);
        	}
    	}
    	return $this->arrTree;
	}	
	
	//暂停使用
	public function getChildNodes2($id){
		$sql = sprintf("SELECT * FROM %s WHERE p_dpt_id=%s",$this->tableName,$id);
		$rows = $this->dao->db->get_all_sql($sql);
		if(isset($rows) && !empty($rows)){
			foreach($rows as $k => $v){
				$this->getChildNodes2($v['department_id']);
			}
		}
	}
	
	//根据父节点查找所有子孙节点
	public function getChildNodes($id){
		$lists = $this->getDepartmentList2();
		foreach($lists as $l){
        	$lists[$l['p_dpt_id']]['son'][$l['department_id']] = &$lists[$l['department_id']];
		}
		return $lists[$id]['son'];
	}

	/************************************************************
	 * @copyright(c): 2017年3月24日
	 * @Author:  yuwen
	 * @Create Time: 上午9:54:48
	 * @qq:32891873
	 * @email:fuyuwen88@126.com
	 * @通过department_id获取上级部门名字和上级部门ID
	 *************************************************************/
	public function getDepartmentName($department_id){
	    $sql = sprintf("SELECT `department_name`,`p_dpt_id` FROM %s WHERE department_id=%s",$this->tableName,$department_id);
	    return $this->dao->db->get_one_sql($sql);
	}

	public function getUsersByDepartmentId($departmentId){
        $sql = "select z.id,z.user,z.UsrName,z.phone,g.`name`,z.level_id,d.department_name,z.Inthetime from cp_zjingjiren_admin z left join zx_department d on z.department_id = d.department_id left join cp_zjingjiren_admin_group g on z.gid = g.id where z.department_id = $departmentId";
        return $this->dao->db->get_all_sql($sql);
    }
}
