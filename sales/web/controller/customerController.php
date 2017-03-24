<?php
if(!defined('IS_INITPHP')) exit('Access Denied!');

//客户管理控制器

class customerController extends baseController{
	public $departmentService 		= NULL;
	public $roleService 			= NULL;
	public $customerService 		= NULL;
	public $customerRecordService 	= NULL;

	public $initphp_list = array('run');
	public function __construct(){
		parent::__construct();
		$this->customerService 		= InitPHP::getService('customer');
		$this->departmentService 	= InitPHP::getService('department');
		$this->roleService 			= InitPHP::getService('adminGroup');
	}
		
	public function joinWhereUrl($uName =null,$uDeparment=null,$uRole = null){
		$where = '';
		$url = '/customer/run';
        if(!empty($uName)){
            $url 	= $url . '/user_name/' . $uName;
            $where .= ' AND investor_real_name="'. $uName. '"';
        }
		if(!empty($uDeparment)){
			$url 	= $url . '/user_department/' . $uDeparment;
			$where .= ' AND inviter_department_id=' . $uDeparment;
		}
		if(!empty($uRole)){
			$url 	= $url . '/user_role/' . $uRole;
			$where .= ' AND inviter_role_id=' . $uRole;
		}
		return array('url' => $url, 'where' => $where);
	}
	
	public function run(){
		$adminService   = InitPHP::getService('admin');
		$userInfo       = $adminService->current_user();
		if(!isset($userInfo)){
			exit(json_encode(array('status' => -1,'message' => '参数错误！')));
		}
		$salesId        = $userInfo['id'];
		$salesId		= 26;//临时调试使用
		$pager 			= $this->getLibrary('pager');
        $page 			= $this->controller->get_gp('page') ? $this->controller->get_gp('page') : 1 ;
		if($page < 1){
			$page = 1;
		}

		$uName 			= $this->controller->get_gp('user_name');
        $uDepartment	= $this->controller->get_gp('user_department');
        $uRole 		 	= $this->controller->get_gp('user_role');
        if(!empty($uName)){
            $this->view->assign('uName', $uName);
        }
        if(!empty($uRole)){
            $this->view->assign('uRole', $uRole);
        }
	    $limit = 10;
		$arrWhereUrl 	= $this->joinWhereUrl($uName,$uDepartment,$uRole); 
		$page 			= ($page-1)*10 ? ($page-1)*10 : 0;
		//获取列表数据根据条件检索查询
		$list = $this->customerService->getCustomers2($page,$limit,$arrWhereUrl['where']);
		$count = $this->customerService->getCustomers2Count($arrWhereUrl['where']);
		$pageHtml = $pager->pager($count['count'], $limit, $arrWhereUrl['url']);

		//部门
		$list2 = $this->departmentService->getDepartmentList2();
        $tree2 = $this->departmentService->generateTree2($list2);
        $html  = $this->departmentService->exportSelectedTree($tree2,$uDepartment);
        $this->view->assign('html', $html);

		//角色
		$userGroup = $this->roleService->adminList();
		$this->view->assign('user_group', $userGroup);
        $this->view->assign('page',$page);
        $this->view->assign('page_html', $pageHtml);
		$this->view->assign('list',$list);
		$this->view->display('customer/run');
	}
}
