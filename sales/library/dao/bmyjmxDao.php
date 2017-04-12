<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 部门业绩Dao
 * @author aaron
 */
class bmyjmxDao extends Dao
{
    public $table_name = 'zx_department';
    
    /**
     * 获取所有的部门列表
     * @param type $departmentId
     * @return type
     */
    public function getDepartmentList($departmentId){
        $sql = "select * from zx_department where p_dpt_id = $departmentId";
        return $this->dao->db->get_all_sql($sql);
    }
    
    public function getDepartmentUser($departmentId,$where=''){
        $sql = "select u.id,z.user,z.gid,z.UsrName,z.phone,g.`name`,z.level_id,d.department_id,d.p_dpt_id,d.department_name,z.Inthetime,z.status,z.update_time from zx_admin z left join zx_department d on z.department_id = d.department_id left join zx_role g on z.gid = g.id left join cp_user u on z.`user` = u.username where z.department_id = $departmentId $where";
        return $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 根据我的部门id，获取我的部门信息
     * @param type $departmentId
     * @return type
     */
    public function getMyDepartment($departmentId){
        $sql = "select * from zx_department where department_id = $departmentId";
        return $this->dao->db->get_one_sql($sql);
    }
    
}
