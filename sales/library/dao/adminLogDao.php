<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 管理员操作日志Dao
 * @author aaron
 */
class adminLogDao extends Dao
{
    public $table_name = 'cp_admin_log';
	
	/**
	 * 根据管理员id写入登录记录
	 * @param $admin_id
	 */
    public function insertLog($admin_id,$text,$ip)
    {
        $loginnum=$this->dao->db->get_count($this->table_name);
        if($loginnum>299)
        {
            $sql = sprintf("delete from %s order by id asc limit 1",$this->table_name);
            $this->dao->db->query($sql);
        }

        $data = array('uid'=>$admin_id,'text'=>$text,'time'=>time(),'ip'=>$ip);
        return $this->dao->db->insert($data,$this->table_name);
	}
    /**
     * 获取登录列表
     * @param $limit
     */
    public function all_list($limit)
    {
        $sql=sprintf("select * from %s a left join cp_admin as b on a.uid=b.id order by a.id desc limit %s",$this->table_name,$limit);
        return  $this->dao->db->get_all_sql($sql);
    }
    /**
     * 获取登录总数
     */
    public function count()
    {
        $sql = sprintf("select count(*) as num from %s as a left join cp_admin as b on a.uid=b.id",$this->table_name);
        $data =  $this->dao->db->get_all_sql($sql);
        return $data[0]['num'];
    }

}