<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/**
 * 后台管理组控制器
 * @author aaron
 */
class roleController extends baseController
{
    public $initphp_list = array('add','add_save','edit','edit_save','del'); //Action白名单

    public function __construct()
    {
        parent::__construct();
        $this->roleService = InitPHP::getService("role");       //获取Service
        $this->adminService = InitPHP::getService("admin");                 //获取Service
	$this->authService = InitPHP::getService("auth");                   //获取权限Service
    }
    /**
     * 默认Action
     * @author aaron
     */
    public function run()
    {
        $this->authService->checkauth("1007");
        $userInfo = $this->adminService->current_user();
        $roleId = '';
        if(isset($userInfo)){
            $roleId = $userInfo['gid'];
        }
        $this->view->assign('role_id',$roleId);
        $list = $this->roleService->adminList();
        $this->view->assign('list', $list);
        $this->view->assign('gid', $userInfo['gid']);
        $department='yes';//默认加样式
        $this->view->assign('department', $department);
        $this->view->display("role/run"); //使用模板
    }
    /**
     * 添加
     * @author aaron
     */
    public function add()
    {
        $this->authService->checkauth("1008");
        $user = $this->adminService->current_user();
        $this->view->assign('action_name', '添加');
        $this->view->assign('action', 'add');
        $this->view->assign('user', $user);
        $userInfo = $this->adminService->current_user();
        $this->view->assign('gid', $userInfo['gid']);
        $department='yes';//默认加样式
        $this->view->assign('department', $department);
        $this->view->display("role/addinfo"); //使用模板
    }
    /**
     * 添加保存
     * @author aaron
     */
    public function add_save()
    {
        if($this->authService->checkauthUser("1009")==false){
            exit(json_encode(array('status' =>0, 'message' => '您没有权限!')));
        }
        $arr = $this->roleService->add_save($_POST);
        if($arr)
        {
            exit(json_encode(array('status' => 1, 'message' => '用户组添加成功!')));
        }
    }
    /**
     * 修改
     * @author aaron
     */
    public function edit()
    {
        $this->authService->checkauth("1010");
        $id = $this->controller->get_gp('id');
        $data=$this->roleService->edit($id);

        $this->view->assign('info', $data['info']);
        $this->view->assign('model_power', $data['model_power']);
        $this->view->assign('action_name', '修改');
        $this->view->assign('action', 'edit');
        $userInfo = $this->adminService->current_user();
        $this->view->assign('gid', $userInfo['gid']);
        $department='yes';//默认加样式
        $this->view->assign('department', $department);
        $this->view->display("role/editinfo");
    }
    /**
     * 修改保存
     * @author aaron
     */
    public function edit_save()
    {
        if($this->authService->checkauthUser("1011")==false){
            exit(json_encode(array('status' =>0, 'message' => '您没有权限!')));
        }
        
        $arr = $this->roleService->edit_save($_POST);
        if($arr)
        {
            exit(json_encode(array('status' => 1, 'message' => '用户组修改成功!')));
        }
    }
    /**
     * 删除
     * @author aaron
     */
    //用户组删除
    public function del()
    {
        if($this->authService->checkauthUser("1012")==false){
            exit(json_encode(array('status' =>0, 'message' => '您没有权限!')));
        }
        $id = $this->controller->get_gp('id');
        $arr = $this->roleService->del($id);
        if($arr==1)
        {
            exit(json_encode(array('status' => 1, 'message' => '用户组删除成功!')));
        }
        if($arr==2)
        {
            exit(json_encode(array('status' => 2, 'message' => '内置管理组无法删除!')));
        }
        if($arr==3)
        {
            exit(json_encode(array('status' => 3, 'message' => '越权操作!')));
        }
    }
}
