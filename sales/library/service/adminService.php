<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 管理员业务层
 * @author aaron
 */
class adminService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->adminDao = InitPHP::getDao("admin");//获取管理员信息
        $this->adminGroupDao = InitPHP::getDao("adminGroup");//获取管理员组信息
    }
	/**
	 * 获取当前用户信息
	 */
    public function current_user()
    {
        $session  = $this->getUtil('session');
        $admin_id = $session->get('admin_id');
        if(empty($admin_id))//如果不存在session跳出到登录
        {
            return false;
        }
        $user= $this->adminDao->adminInfo($admin_id);
        $user_group= $this->adminGroupDao->adminGroupInfo($user['gid']);

        $user['gname']=$user_group['name'];
        $user['model_power']=$user_group['model_power'];
        $user['class_power']=$user_group['class_power'];
        $user['status_power']=$user_group['status_power'];
        $user['grade']=$user_group['grade'];
        $user['keep']=$user_group['keep'];
        return $user;
    }
    /************************************************************
     * @copyright(c): 2017年1月17日
     * @Author:  yuwen
     * @Create Time: 下午2:02:58
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @通过业务账号的id查询投资系统内注册的账号id
     * @这个id可以查询当前登录业务系统账号自己的投资账号id，通过id可以查询他自己的订单
     *************************************************************/
    public function GetToZiXiTongUserId($admin_id){
        $id= $this->adminDao->GetToZiXiTongUserId($admin_id);
        if(isset($id['id'])){
            return $id['id'];
        }else{
            return 0;
        }
    }
    
    /************************************************************
     * @copyright(c): 2017年1月17日
     * @Author:  yuwen
     * @Create Time: 下午2:02:58
     * @通过投资系统的UID获取业务系统的ADMIN_ID
     *************************************************************/
    public function GetToZiXiTongAdminId($uid){
        $data= $this->adminDao->GetToZiXiTongAdminId($uid);
        return $data;
    }
    /**
     * 获取用户列表
     */
    public function admin_list($where="")
    {
        $user=$this->current_user();
        return $this->adminDao->admin_list($user['grade'],$where);
    }
    /*
     *  根据用户名、手机、信箱检测用户是否存在
     */
    public function isUser($data)
    {
        if(preg_match("/^[\w.\-]+@(?:[a-z0-9]+(?:-[a-z0-9]+)*\.)+[a-z]{2,6}$/",$data))
        {
            $info = $this->adminDao->get_email($data);
			return $info;
        }else if(preg_match("/^13[0-9]{9}|14[0-9]{9}|15[0-9]{9}|17[0-9]{9}|18[0-9]{9}$/",$data))
        {
            $info = $this->adminDao->get_phone($data);
			return $info;
        }else{
			$admin = $this->adminDao->getAdmin($data);
            $info = $this->adminDao->get_username($data);
			if($admin) return 2;
			if($info)  return 3;
        }
        
    }
    /**
     * 添加
     */
    public function add_save($data,$admin_id)
    {
        $data['password']=md5($data['password']);
        unset($data['password2']);
		$data['regtime']  = time();
		
		$admin = $this->adminDao->getAdmin($data['user']);//检查在业务系统中是否重名
		if($admin) return 2;
		
		$info = $this->adminDao->get_username($data['user']);//检查在投资系统中是否存在账号
		if($info){
			//根据身份证号检查是否一致，如果一致说明是同一人，只在业务系统中开通经纪人账户
			$userHuifu = $this->adminDao->getUsrNameAndIdNo($data['IdNo']);
			if($userHuifu){
				//写入业务系统
				$data['tuijianren'] = $admin_id;
				$arr = $this->adminDao->add_save($data);
				if($arr) return 3;
			}else{
				return 4;
			}
		}else{
			//如果投资系统中不存在重复账号，但是身份证存在，说明不是同一人，不可以开通业务账号
			$userHuifu = $this->adminDao->getUsrNameAndIdNo($data['IdNo']);
			if($userHuifu){
				return 7;
			}else{
				/*
				 * 开通业务系统账号同时开户投资系统账号
				 */
				
				//写入业务系统
				$data['tuijianren'] = $admin_id;
				$this->adminDao->add_save($data);
				
				//接口注册投资系统
				$curl = $this->getLibrary('curl'); 
				$url = "http://api.baihedai.com.cn/zhiXiaoXiTong/register/?t=".json_encode($data);
				$arr = $curl->get($url);
				if($arr == '123'){
					$admin = $this->adminDao->getAdmin($data['user']);//根据账户名查数据库是否存在
					if($admin){//如果存在删除该记录
						$this->adminDao->del($admin['id']);
						return 123;//用户名必须要6-16位字母、数字和下划线
					}
				}elseif($arr == '234'){
					$admin = $this->adminDao->getAdmin($data['user']);//根据账户名查数据库是否存在
					if($admin){//如果存在删除该记录
						$this->adminDao->del($admin['id']);
						return 234;//用户名只能包含数字、字母、下划线，不能使用特殊字符
					}
				}elseif($arr == '345'){
					$admin = $this->adminDao->getAdmin($data['user']);//根据账户名查数据库是否存在
					if($admin){//如果存在删除该记录
						$this->adminDao->del($admin['id']);
						return 345;
					}
				}elseif($arr == '456'){
					$admin = $this->adminDao->getAdmin($data['user']);//根据账户名查数据库是否存在
					if($admin){//如果存在删除该记录
						$this->adminDao->del($admin['id']);
						return 456;
					}
				}elseif($arr == '567'){
					$admin = $this->adminDao->getAdmin($data['user']);//根据账户名查数据库是否存在
					if($admin){//如果存在删除该记录
						$this->adminDao->del($admin['id']);
						return 567;
					}
				}else{
					return 678;
				}
			}
		}
    }
    /**
     * 根据id查详情
     */
    public function adminInfo($admin_id)
    {
        return $this->adminDao->adminInfo($admin_id);
    }
    /**
     * 修改
     */
    public function edit($id)
    {
        $this->adminService = InitPHP::getService("admin");//获取Service
        $this->adminGroupService = InitPHP::getService("adminGroup");//获取Service
        $data['user_group'] = $this->adminGroupService->adminList();
        $info = $this->adminService->adminInfo($id);
        $info_group = $this->adminGroupService->info($info['gid']);
        $user = $this->adminService->current_user();
        if($info_group['grade']<$user['grade']){
            return 4;
        }
        $data['info']=$info;
        $data['info_group']=$info_group;
        $data['user']=$user;
        return $data;
    }
    /**
     * 修改保存
     */
    public function edit_save($data)
    {
        if ($data['password'])
        {
            if (empty($data['password2']))
            {
                return 5;
            }
            if($data['password']<>$data['password2'])
            {
                return 6;
            }
            $data['password']=md5($data['password']);
        }else{
            unset($data['password']);
        }
		
		$user = $this->adminDao->getAdmin($data['user']);
		if($user)
		{
			return 7;
		}
        unset($data['password2']);
        $arr = $this->adminDao->edit_save($data);
		if($arr) return 8;
    }
    /**
     * 删除
     */
    public function del($id)
    {
        $this->adminService = InitPHP::getService("admin");//获取Service
        $this->adminGroupService = InitPHP::getService("adminGroup");//获取Service
        $info=$this->adminService->adminInfo($id);
        if($info['keep']==1)
        {
            return 9;
        }
        $info_group = $this->adminGroupService->info($info['gid']);
        $user = $this->adminService->current_user();
        if($info_group['grade']<$user['grade']){
            return 11;
        }
        $arr = $this->adminDao->del($id);
		if($arr) return 10;
    }
}
