<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 主页控制器
 * @author aaron
 */
class indexController extends baseController
{
    public function __construct(){
        parent::__construct();
        $this->adminService = InitPHP::getService("admin");         //获取Service
    }
	public $initphp_list = array('home');

	/**
	 * 默认action
	 */
	public function run()
    {
        $list = $this->adminService->current_user();
        $this->view->assign('gid', $list['gid']);
        $this->view->assign('list', $list);
        $this->view->display("index/run");
	}

    /**
     * 欢迎页
     */
    public function home()
    {
        $userinfo = $this->adminService->current_user();
        /*
         * @所属部门
         */
        $departmentService = InitPHP::getService("department");
        $departmentName = $departmentService->getDepartmentName(intval($userinfo['department_id']));
        $this->view->assign('departmentName', $departmentName);
        /*
         * @所属职位
         */
        $adminGroupService = InitPHP::getService("adminGroup");
        $position = $adminGroupService->info(intval($userinfo['gid']));
        $this->view->assign('position', $position['name']);
        /*
         * @邀请可数数量
         */
        $adminService = InitPHP::getService("admin");
        $inviteCustomersService = InitPHP::getService("inviteCustomers");
        $uid = $adminService->GetToZiXiTongUserId(intval($userinfo['id']));
        $uidList = $inviteCustomersService->getInviteCustomersUidList($uid);
        $this->view->assign('UidNumber',count($uidList));//邀请的客户数量
        
        
        $this->view->assign('list', $userinfo);
        $this->view->display("index/home");
    }
}
