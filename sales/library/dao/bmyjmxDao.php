<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 部门业绩明细Dao
 * @author aaron
 */
class bmyjmxDao extends Dao
{
    public $table_name = 'zx_department';
    
    /**
     * 获取所有的部门列表
     * @param type $department_id
     * @return type
     */
    public function getDepartmentList($department_id){
        $sql = "select * from zx_department where p_dpt_id = $department_id";
        return $this->dao->db->get_all_sql($sql);
    }
    
    public function getDepartmentUser($department_id,$where=''){
        $sql = "select z.id,z.user,z.UsrName,z.phone,g.`name`,z.level_id,d.department_name,z.Inthetime from cp_zjingjiren_admin z left join zx_department d on z.department_id = d.department_id left join cp_zjingjiren_admin_group g on z.gid = g.id where z.department_id = $department_id $where";
        return $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 根据我的部门id，获取我的部门信息
     * @param type $department_id
     * @return type
     */
    public function getMyDepartment($department_id){
        $sql = "select * from zx_department where department_id = $department_id";
        return $this->dao->db->get_one_sql($sql);
    }
    
}
