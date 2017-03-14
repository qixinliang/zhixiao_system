<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 管理员组Dao
 * @author aaron
 */
class adminGroupDao extends Dao
{
    public $table_name = 'cp_zjingjiren_admin_group';
	
	/**
	 * 根据管理员组id查详细资料
	 * @param $gid
	 */
    public function adminGroupInfo($gid)
    {
        $data = array('id'=>$gid);
        return $this->dao->db->get_one_by_field($data,$this->table_name);
	}
    /**
     * 获取用户组列表
     * @param $data
     */
    public function adminList($user)
    {
        $sql = sprintf("select * from %s where grade= %s",$this->table_name,$user);
        return  $this->dao->db->get_all_sql($sql);
    }
    /**
     * 添加保存
     * @param $data
     */
    public function add_save($data)
    {
        return $this->dao->db->insert($data, $this->table_name); //操作-插入一条数据
    }
    /**
     * 根据id获取组信息
     * @param $data
     */
    public function info($id)
    {
        return $this->dao->db->get_one_by_field(array('id'=>$id),$this->table_name);
    }
    /**
     * 修改保存
     * @param $data
     */
    public function edit_save($data)
    {
        return $this->dao->db->update_by_field($data, array('id' => $data['id']), $this->table_name); //根据条件更新数据
    }
    /**
     * 删除
     * @param $id
     */
    public function del($id)
    {
        return $this->dao->db->delete_by_field(array('id'=>$id), $this->table_name);
    }
}
