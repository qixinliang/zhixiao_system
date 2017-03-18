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
		
	public function joinWhereUrl($uName = '',$uDeparment='',$uRole = ''){
		$where = '';
		$url = '/customer/run';
        if($uName != ''){
            $url 	= $url . '/user_name/' . $uName;
            $where .= ' AND inviter_name="'. $uname. '"';
        }
		if($uDepartment != ''){
			$url 	= $url . '/user_department/' . $uDepartment;
			$where .= ' AND inviter_depatment_id=' . $uDepartment;
		}

		if($uRole != ''){
			$url 	= $url . '/user_role/' . $uRole;
			$where .= ' AND inviter_depatment_id=' . $uRole;
		}
		return array('url' => $url, 'where' => $where);
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

		$pager 			= $this->getLibrary('pager');
        $page 			= $this->controller->get_gp('page') ? $this->controller->get_gp('page') : 1 ;
		$uName 			= $this->controller->get_gp('user_name');
        $uDepartment	= $this->controller->get_gp('user_department');
        $uRole 		 	= $this->controller->get_gp('user_role');
			
		$arrWhereUrl 	= $this->joinWhereUrl($uName,$uDepartment,$uRole); 
		$page 			= ($page-1)*10 ? ($page-1)*10 : 0;

		$list = $this->customerService->getCustomers2($page,10,$arrWhereUrl['where']);
		$count = $this->customerService->getCustomers2Count($arrWhereUrl['where']);
		$pageHtml = $pager->pager($count['count'], 10, $arrWhereUrl['url']);
		//$list = $this->customerService->getCustomers($where);


		/*
		$url = '/customer/run';
		
		//在数组里面检索
		if(isset($uName) || isset($uDepartment) || $uRole){
			$tmpArray = array();
			foreach($list as $k => $v){
				if($uName == $v['inviter_name']){
					$tmpArray[] = $v;
					$url .= '/inviter_name/'.$uName;
				}
				if($uDepartment == $v['inviter_department_id']){
					$tmpArray[] = $v;
					$url .= '/inviter_department_id/'.$uDepartment;
				}
				if($uRole == $v['inviter_role_id']){
					$tmpArray[] = $v;
					$url .= '/inviter_role_id/'.$uRole;
				}
			}
			$list = $tmpArray();
		}
		

		//分页开始...
		$TeamUtilsService = InitPHP::getService("TeamUtils");
		$limit    = 10;              //每页显示多少条
        $count    = count($list);    //数据总条数
		var_dump($count);
		if(!$count or $count==0){    //增加个判断用于页面分页样式显示
			$count = 1;                               
		}
        $pages    = $this->pages($limit, $url,$count);
        $list     = $TeamUtilsService->cuttingDataPage($url,$limit,$list);//显示数据处理

		$this->view->assign('list',$list['list']);
		*/
        $this->view->assign('page',$page);
        $this->view->assign('page_html', $pageHtml);
		$this->view->assign('list',$list);
		$this->view->display('customer/run');
	}
}
