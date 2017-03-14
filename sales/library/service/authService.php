<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 *  权限层
 * @author aaron
 */
class authService extends Service
{

    public function __construct()
    {
        parent::__construct();
		$this->adminService = InitPHP::getService("admin");//获取管理员信息
    }
	
  	public function checkauth($auth)
	{
        $user=$this->adminService->current_user();
        $menu_power=$user['model_power'];
		$arr=explode(",",$menu_power);
		if($user['keep']==1)
		{
			
		}else{
			$str=in_array($auth,$arr,true);
			if(!$str){
				echo "<script language='javascript'>alert('对不起，你没有执行该操作权限！');</script>";
				exit();
			}
		}
	}
	/************************************************************
	 * @copyright(c): 2017年1月9日
	 * @Author:  yuwen
	 * @Create Time: 上午10:41:47
	 * @qq:32891873
	 * @email:fuyuwen88@126.com
	 * @检查用户是否有权限显示隐藏手机号和用户返回真假
	 *************************************************************/
	public function checkauthUser($auth){
	    $user=$this->adminService->current_user();
	    $menu_power=$user['model_power'];
	    $arr=explode(",",$menu_power);
	    if($user['keep']==1)
	    {
	        return true;	//超级管理员通过验证
	    }else{
	        $str=in_array($auth,$arr,true);
	        if(!$str){
	           return false; //未通过验证
	        }
	        return true;
	    }
	    return false;        //默认未通过验证
	}
}