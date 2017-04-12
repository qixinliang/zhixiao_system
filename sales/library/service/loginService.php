<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 管理员登录业务层
 * @author aaron
 */
class loginService extends Service
{
	/**
	 * 判断管理员信息是否正确
	 */
    public function isLogin()
    {
        $session  = $this->getUtil('session');
        $admin_id = $session->get('admin_id');
        if(empty($admin_id))//如果不存在session跳出到登录
        {
            return false;
        }
        $this->adminDao = InitPHP::getDao("admin");//获取管理员信息
        return $this->adminDao->adminInfo($admin_id);
	}

    /**
     * 检测用户输入信息
     */
    public function check($user,$password,$ip=null)
    {
        $this->adminDao = InitPHP::getDao("admin");//获取管理员信息
        $admin = $this->adminDao->adminNamePassWord($user,$password);
        if($admin)
        {
            $data = array('logintime'=>time(),'ip'=>$ip,'loginnum'=>$admin['loginnum']+1);
            $this->adminDao->adminInfoUpdate($admin['id'],$data);//更新管理员登录信息
            return $admin;
        }
        return false;
    }

}
