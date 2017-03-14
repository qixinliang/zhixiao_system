<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/**
 * 后台管理组控制器
 * @author aaron
 */
class admingroupController extends baseController
{
    public $initphp_list = array('add','add_save','edit','edit_save','del'); //Action白名单

    public function __construct()
    {
        parent::__construct();
        $this->adminGroupService = InitPHP::getService("adminGroup");       //获取Service
        $this->adminService = InitPHP::getService("admin");                 //获取Service
		$this->authService = InitPHP::getService("auth");                   //获取权限Service
    }
    /**
     * 默认Action
     * @author aaron
     */
    public function run()
    {
		$this->authService->checkauth("1017");
        $list = $this->adminGroupService->adminList();
        $this->view->assign('list', $list);
        $this->view->display("admingroup/run"); //使用模板
    }
    /**
     * 添加
     * @author aaron
     */
    public function add()
    {
        $this->authService->checkauth("1018");
        $user = $this->adminService->current_user();
        $this->view->assign('action_name', '添加');
        $this->view->assign('action', 'add');
        $this->view->assign('user', $user);
        $this->view->display("admingroup/addinfo"); //使用模板
    }
    /**
     * 添加保存
     * @author aaron
     */
    public function add_save()
    {
        if($this->authService->checkauthUser("1019")==false){
            exit(json_encode(array('status' =>0, 'message' => '您没有权限!')));
        }
        $arr = $this->adminGroupService->add_save($_POST);
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
        $this->authService->checkauth("1020");
        $id = $this->controller->get_gp('id');
        $data=$this->adminGroupService->edit($id);
        if($data==0)
        {
            exit(json_encode(array('status' => 0, 'message' => '越权操作!')));
        }
        $this->view->assign('info', $data['info']);
        $this->view->assign('user', $data['user']);
        $this->view->assign('model_power', $data['model_power']);
        $this->view->assign('action_name', '修改');
        $this->view->assign('action', 'edit');
        $this->view->display("admingroup/editinfo");
    }
    /**
     * 修改保存
     * @author aaron
     */
    public function edit_save()
    {
        if($this->authService->checkauthUser("1021")==false){
            exit(json_encode(array('status' =>0, 'message' => '您没有权限!')));
        }
        
        $arr = $this->adminGroupService->edit_save($_POST);
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
        if($this->authService->checkauthUser("1022")==false){
            exit(json_encode(array('status' =>0, 'message' => '您没有权限!')));
        }
        $id = $this->controller->get_gp('id');
        $arr = $this->adminGroupService->del($id);
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
