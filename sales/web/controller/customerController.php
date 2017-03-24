<?php
if(!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * @客户管理控制器
 */
class customerController extends baseController{
	public $departmentService 		= NULL;
	public $roleService 			= NULL;
	public $customerService 		= NULL;
	public $customerRecordService 	= NULL;

	public $initphp_list = array('run','adjust','adjustSave','record');
	public function __construct(){
		parent::__construct();
		$this->customerService 		 = InitPHP::getService('customer');
		$this->customerRecordService = InitPHP::getService('customerRecord');
		$this->departmentService 	 = InitPHP::getService('department');
		$this->roleService 			 = InitPHP::getService('adminGroup');
	}

	/*
	 *@分配记录
	 */
	public function record(){
		$pager 			= $this->getLibrary('pager');
        $page 			= $this->controller->get_gp('page') ? $this->controller->get_gp('page') : 1 ;
		if($page < 1){
			$page = 1;
		}

		$investorName = $this->controller->get_gp('investor_name');
		if(!empty($investorName)){
			$this->view->assign('investor_name',$investorName);
		}
		$limit 			= 10;
		$page 			= ($page-1)*10 ? ($page-1)*10 : 0;
		$arrWhereUrl 	= $this->joinWhereUrl2($investorName); 

		$records  = $this->customerRecordService->getRecords($page,$limit,$arrWhereUrl['where']);
		$count    = $this->customerRecordService->getRecordsCount($arrWhereUrl['where']);
		$pageHtml = $pager->pager($count['count'], $limit, $arrWhereUrl['url']);

		if(!isset($records) || empty($records)){
			exit(json_encode(array('status' => -1,'message' => '分配记录为空')));
		}
		$fRecords = array();
		foreach($records as $k => $v){
			$tmpArr = array(
				'record_id'				=> $v['record_id'],
				'investor_id'			=> $v['investor_id'],
				'investor_name'			=> $v['investor_name'],
				'origin_inviter_id'		=> $v['origin_inviter_id'],
				'origin_inviter_name'	=> $v['origin_inviter_name'],
				'new_inviter_id'		=> $v['new_inviter_id'],
				'new_inviter_name'		=> $v['new_inviter_name'],
				'create_time' 			=> date('Y-m-d H:i:s',$v['create_time']),
			);
			$fRecords[] = $tmpArr;
		}
        $this->view->assign('page',$page);
        $this->view->assign('page_html', $pageHtml);

		$this->view->assign('records',$fRecords);
		$this->view->display('customer/record');
	}

	public function joinWhereUrl2($investorName = null){
		$where = '';
		$url = '/customer/record';
        if(!empty($investorName)){
            $url 	= $url . '/investor_name/' . $investorName;
            $where .= ' AND investor_name="'. $investorName. '"';
        }
		return array('url' => $url, 'where' => $where);
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

		//来个转换函数。。。
		$fList = array();
		foreach($list as $k => $v){
			$inviterDptName = $this->departmentService->getDepartmentName($v['inviter_department_id']);
			if(!isset($inviterDptName) || empty($inviterDptName)){
				$inviterDptName = '无';
			}

			$inviterRoleName = '无';
			$row = $this->roleService->info($v['inviter_role_id']);
			if(isset($row) && !empty($row)){
				$inviterRoleName = $row['name'];
			}
			$tmpList = array(
				'customer_pool_id' 		=> $v['customer_pool_id'], 
				'investor_id' 			=> $v['investor_id'], 
				'investor_login_name' 	=> $v['investor_login_name'], 
				'investor_real_name' 	=> $v['investor_real_name'], 
				'investor_cellphone' 	=> $v['investor_cellphone'], 
				'create_time' 			=> date('Y-m-d H:i:s',$v['create_time']),
				'inviter_name' 			=> $v['inviter_name'], 
				'inviter_dpt_name' 		=> $inviterDptName,
				'inviter_role_name' 	=> $inviterRoleName,
				//FIXME,离职的时候把销售的离职日期写到数据库中.
				'inviter_off_time' 		=> date('Y-m-d H:i:s',time()),
				'invest_status' 		=> $v['invest_status']
			);
			$fList[] = $tmpList;
		}

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
		$this->view->assign('list',$fList);
		$this->view->display('customer/run');
	}

	/*
     *@客户重新分配
     */
	public function adjust(){
		/*
         *run函数，点击重新分配按钮传递过来的是公共池的ID
		 */
		$customerPoolId = intval($this->controller->get_gp('customer_pool_id'));
		$rows 			= $this->customerService->info($customerPoolId);
		if(!isset($rows) || empty($rows)){
			exit(json_encode(array('status' => -1,'message' => 'Customer pool id not Found！')));
		}
	
		//客户信息	
		$investorId 		= $rows['investor_id'];
		$investorName 		= $rows['investor_real_name'];

		//销售信息
		$originInviterId 	= $rows['inviter_id'];
		$originInviterName 	= $rows['inviter_name'];

		//新销售ID
		//$newInviterId 		= $this->controller->get_gp('new_inviter_id');
		
		//提供给页面的变量
		$this->view->assign('investor_id',$investorId);
		$this->view->assign('investor_name',$investorName);
		$this->view->assign('origin_inviter_id',$originInviterId);
		$this->view->assign('origin_inviter_name',$originInviterName);
		//$this->view->assign('new_inviter_id',$newInviterId);
		$this->view->assign('action','adjustSave');

		$this->view->display('customer/adjust');
	}

	//保存至数据库
	public function adjustSave(){
		$investorId 		= $this->controller->get_gp('investor_id');
		$investorName 		= $this->controller->get_gp('investor_name');
		$originInviterId 	= $this->controller->get_gp('origin_inviter_id');
		$originInviterName  = $this->controller->get_gp('origin_inviter_name');
		$newInviterId 		= $this->controller->get_gp('new_inviter_id');
		$newInviterName 	= $this->controller->get_gp('new_inviter_name');
		$data = array(
			'investor_id' 			=> $investorId,
			'investor_name' 		=> $investorName,
			'origin_inviter_id' 	=> $originInviterId,
			'origin_inviter_name' 	=> $originInviterName,
			'new_inviter_id' 		=> $newInviterId,
			'new_inviter_name' 		=> $newInviterName,
			'create_time' 			=> time(),
			'update_time' 			=> time(),
		);
		//写入历史纪录表
		$ret = $this->customerRecordService->addSave($data);
		//可以用firebug调试看出，成功后$ret为返回的数据库主键字段
		//FIXME 需要清除公共池的数据，在前端页面里需要传过来customer_pool_id
		if($ret > 0){
            exit(json_encode(array('status' => 1, 'message' => '客户分配成功!')));
		}else{
            exit(json_encode(array('status' => -1, 'message' => '客户分配失败!')));
		}
	}
}
