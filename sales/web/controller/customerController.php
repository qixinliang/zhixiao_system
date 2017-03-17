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
		$uName 			= $this->controller->get_gp('user_name');
        $uDepartment	= $this->controller->get_gp('user_department');
        $uRole 		 	= $this->controller->get_gp('user_role');

		$where = '1=1';
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
		$this->view->assign('list',$list);
		$this->view->display('customer/run');

		/*
		if(!isset($salesId) || empty($salesId)){
			exit(json_encode(array('status' => -1,'message' => '参数错误！')));
		}
		//写死，模拟测试。
		$salesId = 26;
		//先入库
		$ret = $this->customerService->addCustomer($salesId);
		var_dump($ret);
		//再取出来展示
		*/
	}
}
