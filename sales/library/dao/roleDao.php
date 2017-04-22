<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 管理员组Dao
 */
class roleDao extends Dao
{
    public $tableName = 'zx_role';
	
	/**
	 * 根据管理员组id查详细资料
	 * @param $gid
	 */
    public function adminGroupInfo($gid)
    {
        $data = array('id'=>$gid);
        return $this->dao->db->get_one_by_field($data,$this->tableName);
	}
    /**
     * 获取用户组列表
     * @param $data
     */
    public function adminList()
    {
        $sql = sprintf("select * from %s",$this->tableName);
        return  $this->dao->db->get_all_sql($sql);
    }
    /**
     * 添加保存
     * @param $data
     */
    public function add_save($data)
    {
        return $this->dao->db->insert($data, $this->tableName); //操作-插入一条数据
    }
    /**
     * 根据id获取组信息
     * @param $data
     */
    public function info($id)
    {
        return $this->dao->db->get_one_by_field(array('id'=>$id),$this->tableName);
    }
    /**
     * 修改保存
     * @param $data
     */
    public function edit_save($data)
    {
        return $this->dao->db->update_by_field($data, array('id' => $data['id']), $this->tableName); //根据条件更新数据
    }
    /**
     * 删除
     * @param $id
     */
    public function del($id)
    {
        return $this->dao->db->delete_by_field(array('id'=>$id), $this->tableName);
    }
}
