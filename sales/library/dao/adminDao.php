<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 管理员Dao
 * @author aaron
 */
class adminDao extends Dao
{
    public $table_name = 'zx_admin';
	
	public function adminInfoEx($adminId){
        $sql=sprintf("SELECT UsrName,department_id,gid FROM %s WHERE id=%s",$this->table_name,$adminId);
		return $this->dao->db->get_one_sql($sql);
	}

    /**
     * 根据管理员id查详细资料
     * @param $admin_id
     */
    public function adminInfo($admin_id)
    {
        $sql=sprintf("SELECT a.*,b.name as gnname from %s as a LEFT JOIN  %s b ON a.gid=b.id where a.id=%s ",$this->table_name,'zx_role',$admin_id);
        return  $this->dao->db->get_one_sql($sql);
    }

    /**
     * 根据管理员用户名与密码查详细资料
     * @param $user,$password
     * @param $status 离职状态 1在职
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

    /*
     * 获取用户列表
     */
    public function admin_list($where,$page,$limit)
    {
        $sql=sprintf("select a.*,b.name as gname,d.department_name,b.privilege from %s a left join zx_role b on a.gid=b.id left JOIN zx_department d on d.department_id = a.department_id where 1=1 %s order by id asc limit $page,$limit",$this->table_name,$where);
        return  $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 获取用户列表数量
     * @param $data array()
     */
    public function admin_list_count($where)
    {
        $sql=sprintf("select count(*) as count from %s a left join zx_role b on a.gid=b.id left JOIN zx_department d on d.department_id = a.department_id where 1=1 %s order by a.id asc",$this->table_name,$where);
        return  $this->dao->db->get_one_sql($sql);
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
        $time = time();
        $data['update_time'] = time();
        return $this->dao->db->update_by_field($data, array('id' => $data['id']), $this->table_name); //根据条件更新数据
    }

	//更新用户
	public function update($data,$id){
        return $this->dao->db->update_by_field($data, array('id' => $id), $this->table_name);
	}
    /**
     * 删除
     * @param $id
     */
    public function del($id,$status)
    {
        $time = time();
        return $this->dao->db->update_by_field(array('status'=>$status,'update_time'=>$time),array('id'=>$id), $this->table_name);
    }
	
    //根据手机号获取详细信息
    public function get_phone($phone)
    {
        return $this->dao->db->get_one_by_field(array('phone' => $phone),"cp_user_info");
    }
    
    //查询当前手机号，是否在直销系统中注册
    public function get_phone_zx($phone)
    {
        return $this->dao->db->get_one_by_field(array('phone' => $phone),"zx_admin");
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
        $sql=sprintf("select a.*,b.name as gname,c.id as uid from %s a left join zx_role b on a.gid=b.id left join cp_user c on a.user=c.username where a.tuijianren=%s and a.gid= 2 order by id asc",$this->table_name,$tuijianren);
        return  $this->dao->db->get_all_sql($sql);
    }
	
    /**
     * 推荐总监列表--->名下经纪人数量
     * @param $data array()
     */
    public function jingjirenCout($tuijianren)
    {
       $sql=sprintf("select a.*,b.name as gname,c.id as uid from %s a ,%s b ,%s c where a.gid=b.id and a.user=c.username and a.tuijianren=%s and a.gid= 3 order by a.id asc",$this->table_name,"zx_role","cp_user",$tuijianren);
       return  $this->dao->db->get_all_sql($sql);
    }
	
    /**
     * 推荐经纪人列表
     * @param $data array()
     */
    public function tuijianjingjiren_list($tuijianren)
    {
        $sql=sprintf("select a.*,b.name as gname,c.id as uid from %s a left join zx_role b on a.gid=b.id left join cp_user c on a.user=c.username where a.tuijianren=%s and a.gid= 3 order by id asc",$this->table_name,$tuijianren);
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
     * @copyright(c): 2017年1月17日
     * @Author:  yuwen
     * @Create Time: 下午2:02:58
     * @通过业务账号的id查询投资系统内注册的账号id
     * @这个id可以查询当前登录业务系统账号自己的投资账号id，通过id可以查询他自己的订单
     *************************************************************/
    public function GetToZiXiTongAdminId($uid){
        $sql=sprintf("SELECT a.id from %s as a LEFT JOIN cp_user as b on b.username=a.user where b.id='%s'",$this->table_name,$uid);
        $data= $this->dao->db->get_one_sql($sql);
        return $data['id'];
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
        $sql=sprintf("select a.*,b.name as gname,c.id as uid from %s a left join zx_role b on a.gid=b.id left join cp_user c on a.user=c.username where a.tuijianren=%s and a.regtime >=%s and a.regtime <=%s and a.gid= 3 order by id asc",$this->table_name,$tuijianren,$start,$end);
        return  $this->dao->db->get_all_sql($sql);
    }
    /************************************************************
     * @copyright(c): 2017年3月28日
     * @Author:  yuwen
     * @Create Time: 下午5:35:01
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @根据department_id获取部门内有多少用户
     *************************************************************/
    public function getdepartmentTheUser($department_id){
        $sql=sprintf("select * from zx_admin a left join zx_role b on  a.gid=b.id left join zx_department c on a.department_id=c.department_id where c.department_id=%s",$department_id);
        return  $this->dao->db->get_all_sql($sql);
    }
	
	//获取特定的部门ID下有多少用户
	public function getUserCount($did){
		$sql = "SELECT count(id) AS count FROM zx_admin WHERE department_id=$did";
		return $this->dao->db->get_one_sql($sql);
	}

	public function getLeftUser($id){
		$sql = sprintf("SELECT * FROM `zx_admin` a 
			LEFT JOIN `zx_role` b ON a.gid=b.id
			LEFT JOIN `zx_department` c ON a.department_id=c.department_id
			WHERE a.id!=%s",$id);
		return $this->dao->db->get_all_sql($sql);
	}
}
