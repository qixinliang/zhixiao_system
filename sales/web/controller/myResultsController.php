<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*
 *@我的业绩控制器
 */
class myResultsController extends baseController{
    public $initphp_list = array('run','createExcel'); //Action白名单

	public $authService  		= NULL;
	public $adminService 		= NULL;
	public $myResultsService 	= NULL;

    public function __construct(){
        parent::__construct();
		$this->authService  	 = InitPHP::getService('auth');
		$this->adminService    	 = InitPHP::getService('admin');
		$this->myResultsService  = InitPHP::getService('myResults');
    }

	public function createExcel(){
        $userinfo = $this->adminService->current_user();
        $res = $this->myResultsService->ResultsList($userinfo);
		$createExcelService = InitPHP::getService('createExcel');
		$createExcelService->run4($res['data']);
	}

    public function run(){
		$this->authService->checkauth('1019');
        $userinfo = $this->adminService->current_user();
        $res = $this->myResultsService->ResultsList($userinfo);
        $renshucount=0;

        if(!empty($res['data'])){
           foreach ($res['data'] as $Key=>$val){
              $renshucount+= $val['yaoqingrencount'];
           } 
        }
        /*
         * @判断当前用户是否有权限访问组织结构cai'dan
         */
        $adminService = InitPHP::getService('admin');
        $userinfo = $adminService->current_user();
        $this->view->assign('gid', $userinfo['gid']);
        
        $this->view->assign('renshucount',$renshucount);
        $this->view->assign('list',$res['data']);
        $myClients='yes';//默认加样式
        $this->view->assign('myClients', $myClients);
        
        
        
        //左侧样式是否显示高亮样式
        $myResultsleftcorpnav = 'yes';
        $this->view->assign('myResultsleftcorpnav', $myResultsleftcorpnav);
        
        
        $this->view->display("myresults/run");
    }
}
