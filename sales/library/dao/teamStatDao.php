<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 团队统计Dao
 * @author aaron
 */

class teamStatDao extends Dao
{
    
    public function getTeamManager(){
        $sql = "select u.id,a.department_id  from zx_admin a left join cp_user u on a.user = u.username left join zx_role  r on a.gid = r.id where r.id =9";
        return $this->dao->db->get_all_sql($sql);
    }
}
