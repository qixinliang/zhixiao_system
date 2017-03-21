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
        $this->view->assign('list', $userinfo);
        
        $this->view->display("index/home");
    }
}
