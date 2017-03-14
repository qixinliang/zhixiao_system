<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/**
 * 修改密码
 * @author aaron
 */
class editpasswordController extends baseController
{
    public $initphp_list = array("edit_save"); //Action白名单

    public function __construct()
    {
        parent::__construct();
        $this->adminService = InitPHP::getService("admin");//获取Service
        $this->adminGroupService = InitPHP::getService("adminGroup");//获取Service
		$this->authService = InitPHP::getService("auth");//获取权限Service
    }
    
    /**
     * 默认Action
     * @author aaron
     */
    public function run()
    {
		$this->authService->checkauth("1013");
        $list = $this->adminService->admin_list();
        $this->view->assign('list', $list);
        $this->view->assign('action', "edit");
        $this->view->display("editpassword/run");
    }
    
    
    /**
     * 修改保存
     * @author aaron
     */
    public function edit_save()
    {
        if($this->authService->checkauthUser("1014")==false){
            exit(json_encode(array('status' => 0, 'message' => '您无权限操作')));
        }
		$token = $this->controller->check_token(); 
		if(!$token) exit;//如果token不存在则退出
		
		$data['id'] = $this->controller->get_gp('id');
		$data['password'] = $this->controller->get_gp('password');
		$data['password2'] = $this->controller->get_gp('password2');
        $arr = $this->adminService->edit_save($data);
        if($arr==5)
        {
            exit(json_encode(array('status' => 5, 'message' => '未填写确认密码')));
        }else if($arr==6)
        {
            exit(json_encode(array('status' => 6, 'message' => '两次密码输入不同')));
        }else if($arr==7)
        {
            exit(json_encode(array('status' => 7, 'message' => '帐号已存在')));
        }else if($arr==8)
        {
            exit(json_encode(array('status' => 8, 'message' => '修改成功!')));
        }
    }
}