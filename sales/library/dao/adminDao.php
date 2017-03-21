<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 管理员Dao
 * @author aaron
 */
class adminDao extends Dao
{
    public $table_name = 'cp_zjingjiren_admin';

    /**
     * 根据管理员id查详细资料
     * @param $admin_id
     */
    public function adminInfo($admin_id)
    {
        $sql=sprintf("SELECT a.*,b.name as gnname from %s as a LEFT JOIN  %s b ON a.gid=b.id where a.id=%s ",$this->table_name,'cp_zjingjiren_admin_group',$admin_id);
        return  $this->dao->db->get_one_sql($sql);
    }

    /**
     * 根据管理员用户名与密码查详细资料
     * @param $user,$password
     */
    public function adminNamePassWord($user,$password)
    {
	   $data = array('user'=>$user,'password'=>$password,'status'=>1);
        return $this->dao->db->get_one_by_field($data,$this->table_name);
    }

    /**
     * 更新管理员信息
     * @param $data array()
     */
    public function adminInfoUpdate($id,$data)
    {
        return $this->dao->db->update($id, $data, $this->table_name);
    }

    /**
     * 获取用户列表
     * @param $data array()
     */
    public function admin_list($grade)
    {
        $sql=sprintf("select a.*,b.name as gname from %s a left join cp_zjingjiren_admin_group b on a.gid=b.id where b.grade>=%s order by id desc",$this->table_name,$grade);
        return  $this->dao->db->get_all_sql($sql);
    }
    /**
     * 检测重复用户
     * @param $data array()
     */
    public function getAdmin($user)
    {
        $sql=sprintf("select * from %s where user = '%s' ",$this->table_name,$user);
        return  $this->dao->db->get_one_sql($sql);
    }
    /**
     * 添加
     * @param $data
     */
    public function add_save($data)
    {
        return $this->dao->db->insert($data, $this->table_name); //操作-插入一条数据
    }

    /**
     * 修改
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
	
    //根据手机号获取详细信息
    public function get_phone($phone)
    {
        return $this->dao->db->get_one_by_field(array('phone' => $phone),"cp_user_info");
    }

    //根据信箱获取详细信息
    public function get_email($email)
    {
        return $this->dao->db->get_one_by_field(array('email' => $email),"cp_user_info");
    }
	
    //根据用户名获取详细信息
    public function get_username($username)
    {
        return $this->dao->db->get_one_by_field(array('username' => $username),"cp_user");
    }
	
    //根据真实名字与身份证号，查询投资系统中是否已经开户实名认证过
    public function getUsrNameAndIdNo($IdNo)
    {
        $sql=sprintf("select * from %s where IdNo = '%s' ","cp_user_huifu",$IdNo);
        return  $this->dao->db->get_one_sql($sql);
    }
	
    /**
     * 推荐总监列表
     * @param $data array()
     */
    public function tuijianzongjian_list($tuijianren)
    {
        $sql=sprintf("select a.*,b.name as gname,c.id as uid from %s a left join cp_zjingjiren_admin_group b on a.gid=b.id left join cp_user c on a.user=c.username where a.tuijianren=%s and a.gid= 2 order by id asc",$this->table_name,$tuijianren);
        return  $this->dao->db->get_all_sql($sql);
    }
	
    /**
     * 推荐总监列表--->名下经纪人数量
     * @param $data array()
     */
    public function jingjirenCout($tuijianren)
    {
       $sql=sprintf("select a.*,b.name as gname,c.id as uid from %s a ,%s b ,%s c where a.gid=b.id and a.user=c.username and a.tuijianren=%s and a.gid= 3 order by a.id asc",$this->table_name,"cp_zjingjiren_admin_group","cp_user",$tuijianren);
       return  $this->dao->db->get_all_sql($sql);
    }
	
    /**
     * 推荐经纪人列表
     * @param $data array()
     */
    public function tuijianjingjiren_list($tuijianren)
    {
        $sql=sprintf("select a.*,b.name as gname,c.id as uid from %s a left join cp_zjingjiren_admin_group b on a.gid=b.id left join cp_user c on a.user=c.username where a.tuijianren=%s and a.gid= 3 order by id asc",$this->table_name,$tuijianren);
        return  $this->dao->db->get_all_sql($sql);
    }
    /************************************************************
     * @copyright(c): 2017年1月17日
     * @Author:  yuwen
     * @Create Time: 下午1:56:18
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @通过业务系统的用户id获取在线系统的投资账号id
     *************************************************************/
    public function GetToZiXiTongUserId($admin_id){
        $sql=sprintf("SELECT b.id from %s as a LEFT JOIN cp_user as b on b.username=a.user where a.id='%s'",$this->table_name,$admin_id);
        return  $this->dao->db->get_one_sql($sql);
    }
    /************************************************************
     * @copyright(c): 2016年12月16日
     * @Author:  yuwen
     * @Create Time: 下午3:34:25
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @经纪人推荐经纪人列表按时间段查询
     *************************************************************/
    public function jingjirentuijianjingjirensereach($tuijianren,$start,$end){
        $sql=sprintf("select a.*,b.name as gname,c.id as uid from %s a left join cp_zjingjiren_admin_group b on a.gid=b.id left join cp_user c on a.user=c.username where a.tuijianren=%s and a.regtime >=%s and a.regtime <=%s and a.gid= 3 order by id asc",$this->table_name,$tuijianren,$start,$end);
        return  $this->dao->db->get_all_sql($sql);
    }
}
