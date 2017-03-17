<?php
if(!defined('IS_INITPHP')) exit('Access Denied!');

//客户管理控制器

class customerController extends baseController{
	public $customerRecordService = NULL;

	public $initphp_list = array('run');
	public function __construct(){
		parent::__construct();
		$this->customerService = InitPHP::getService('customer');
	}
	
	public function run($salesId){
		$adminService   = InitPHP::getService('admin');
		$userInfo       = $adminService->current_user();
		if(!isset($userInfo)){
			exit(json_encode(array('status' => -1,'message' => '参数错误！')));
		}
		$salesId        = $userInfo['id'];
		$salesId		= 26;
		$ret 			= $this->customerService->addCustomer($salesId);
       	echo '<pre>';
       	print_r($ret);
       	echo '</pre>';

		$uName 			= $this->controller->get_gp('user_name');
        $uDepartment	= $this->controller->get_gp('user_department');
        $uRole 		 	= $this->controller->get_gp('user_role');

		$where = 'WHERE 1=1';
		if(isset($uName) && !empty($uName)){
			$where .= ' AND inviter_name="'. $uName. '"';
			$this->view->assign('user_name',$uName);
		}

		if(isset($uDepartment) && !empty($uDepartment)){
			$where .= ' AND inviter_department_id="'. $uDepartment. '"';
			$this->view->assign('user_department',$uName);
		}

		if(isset($uRole) && !empty($uDepartment)){
			$where .= ' AND inviter_role_id="'. $uRole. '"';
			$this->view->assign('user_role',$uRole);
		}
		$list = $this->customerService->getCustomers($where);
		var_dump($list);
		$this->view->assign('list',$list);
		$this->view->display('customer/run');

	}
}
