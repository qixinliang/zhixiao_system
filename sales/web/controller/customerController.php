<?php
if(!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * @客户管理控制器
 */
class customerController extends baseController{
	public $adminService 			= NULL;
	public $departmentService 		= NULL;
	public $roleService 			= NULL;
	public $customerService 		= NULL;
	public $customerRecordService 	= NULL;

	public $initphp_list = array('run','adjust','adjustSave','record');
	public function __construct(){
		parent::__construct();
		$this->adminService 			= InitPHP::getService('admin');
		$this->customerService 			= InitPHP::getService('customer');
		$this->customerRecordService 	= InitPHP::getService('customerRecord');
		$this->departmentService 		= InitPHP::getService('department');
		$this->roleService 				= InitPHP::getService('role');
		$this->authService              = InitPHP::getService('auth');
	}

	/*
     * @客户管理的首页
	 */
	public function run(){
		$this->authService->checkauth('1025');
		//获取当前登陆用户
		$adminService = InitPHP::getService('admin');
		$userInfo     = $adminService->current_user();
		if(!isset($userInfo)){
			exit(json_encode(array('status' => -1,'message' => 'Current User not found！')));
		}
		$salesId  = $userInfo['id'];

		$pager = $this->getLibrary('pager');
        $page  = $this->controller->get_gp('page')? $this->controller->get_gp('page') : 1 ;
		if($page < 1){
			$page = 1;
		}

		$uName 			= urldecode($this->controller->get_gp('user_name'));
        $uDepartment	= $this->controller->get_gp('user_department');
        $uRole 		 	= $this->controller->get_gp('user_role');
        if(!empty($uName)){
            $this->view->assign('uName', $uName);
        }
        if(!empty($uRole)){
            $this->view->assign('uRole', $uRole);
        }

	    $offset = 10;
		$cond   = $this->searchCond($uName,$uDepartment,$uRole); 
		$page   = ($page-1) * $offset ? ($page-1) * $offset : 0;

		//获取客户公共池的数据列表及数量
		$count    = $this->customerService->getCustomersCount($cond['where']);
		$list     = $this->customerService->getCustomers($page,$offset,$cond['where']);

		$this->view->assign('count',$count['count']);
		$pageHtml = $pager->pager($count['count'], $offset, $cond['url']);

		//数据转换
		$fList = array();
		foreach($list as $k => $v){
			$inviterDptName = $this->departmentService->getDepartmentName2($v['inviter_department_id']);
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
				'inviter_off_time' 		=> date('Y-m-d H:i:s',$v['inviter_off_time']),
				'invest_status' 		=> $v['invest_status']
			);
			$fList[] = $tmpList;
		}

		//部门筛选
		$list2 = $this->departmentService->getDepartmentList2();
        $tree2 = $this->departmentService->generateTree2($list2);
        $html  = $this->departmentService->exportSelectedTree($tree2,$uDepartment);
        $this->view->assign('html', $html);

		//角色筛选
		$userGroup = $this->roleService->adminList();
		$this->view->assign('user_group', $userGroup);
        
		/*
		 * @判断当前用户是否有权限访问组织结构cai'dan
		 */
		$this->view->assign('gid', $userInfo['gid']);
		
		
        $this->view->assign('page',$page);
        $this->view->assign('page_html', $pageHtml);
		$this->view->assign('list',$fList);
		
		$customer='yes';//默认加样式
		$this->view->assign('customer', $customer);
		
		
		//左侧样式是否显示高亮样式
		$customerleftcorpnav = 'yes';
		$this->view->assign('customerleftcorpnav', $customerleftcorpnav);
		
		
		$this->view->display('customer/run');
	}



	public function searchCond2($investorName = null){
		$where = '';
		$url = '/customer/record';
        if(!empty($investorName)){
            $url 	= $url . '/investor_name/' . $investorName;
            $where .= ' AND investor_name="'. $investorName. '"';
        }
		return array('url' => $url, 'where' => $where);
	}
		
	public function searchCond($uName =null,$uDeparment=null,$uRole = null){
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
	

	/*
     *@客户重新分配
     */
	public function adjust(){
		$this->authService->checkauth('1026');
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

		//所有用户信息去除刚才的销售
		$leftUser = $this->adminService->getLeftUser($originInviterId);

		$leftInviterArr = array();
		if(isset($leftUser) && !empty($leftUser)){
			foreach($leftUser as $k => $v){
				$id = $v['id'];

				//部门-角色-名字
				$str = $v['department_name'].'-'.$v['name'].'-'.$v['user'];
				$tmpArr = array(
					'id'  => $id,
					'str' => $str,
				);
				$leftInviterArr[] = $tmpArr;
			}
		}

		//提供给页面的变量
		$this->view->assign('investor_id',$investorId);
		$this->view->assign('investor_name',$investorName);
		$this->view->assign('origin_inviter_id',$originInviterId);
		$this->view->assign('origin_inviter_name',$originInviterName);
		$this->view->assign('left_inviter_arr',$leftInviterArr);
		$this->view->assign('customer_pool_id',$customerPoolId);
		$this->view->assign('action','adjustSave');
        
		/*
		 * @判断当前用户是否有权限访问组织结构cai'dan
		 */
		$adminService = InitPHP::getService('admin');
		$userinfo = $adminService->current_user();
		$this->view->assign('gid', $userinfo['gid']);
		
		$customer='yes';//默认加样式
		$this->view->assign('customer', $customer);
		
		//左侧样式是否显示高亮样式
		$customerleftcorpnav = 'yes';
		$this->view->assign('customerleftcorpnav', $customerleftcorpnav);
		
		$this->view->display('customer/adjust');
	}

	//保存至数据库
	public function adjustSave(){
		$this->authService->checkauth('1027');
		$customerPoolId     = $this->controller->get_gp('customer_pool_id');
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


		//查询当前客户是否已经被分配过，如果分配过，把数据分配记录里面的当前负责人清零。
		$clientUid = $this->customerService->getClientUid($investorId);
		if(!empty($clientUid) && $clientUid>0){
		    //修改用户记录表，把zx_customer_record表 principal字段默认设置为0
		  $this->customerService->updateCustomerRecord($investorId);
		}
		/*
		 *写入历史纪录表，插入成功，$ret为返回的数据库primary key
		 */
		$ret = $this->customerRecordService->addSave($data);
		if($ret > 0){
			//清除客户公共池的数据
			$del = $this->customerService->del($customerPoolId);
            exit(json_encode(array('status' => 1, 'message' => 'Adjust customer success!')));
		}else{
            exit(json_encode(array('status' => -1, 'message' => 'Adjust customer failed!')));
		}
	}
        
    /*
	 *@分配记录
	 */
	public function record(){
		$this->authService->checkauth('1028');
		$pager = $this->getLibrary('pager');
        $page  = $this->controller->get_gp('page')?
				$this->controller->get_gp('page') : 1 ;
		if($page < 1) $page = 1;

		$investorName = urldecode($this->controller->get_gp('investor_name'));
		if(!empty($investorName)){
			$this->view->assign('investor_name',$investorName);
		}
		$offset = 10;
		$page	= ($page-1)*$offset? ($page-1)*$offset : 0;
		$cond 	= $this->searchCond2($investorName);

		$count    = $this->customerRecordService->getRecordsCount($cond['where']);
		$records  = $this->customerRecordService->getRecords($page,$offset,$cond['where']);

		$this->view->assign('count',$count['count']);
		$pageHtml = $pager->pager($count['count'], $offset, $cond['url']);

		$fRecords = array();
		if(isset($records) && !empty($records)){
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
		}
        $this->view->assign('page',$page);
        $this->view->assign('page_html', $pageHtml);
        
        /*
         * @判断当前用户是否有权限访问组织结构cai'dan
         */
        $adminService = InitPHP::getService('admin');
        $userinfo = $adminService->current_user();
        $this->view->assign('gid', $userinfo['gid']);
        $customer='yes';//默认加样式
        $this->view->assign('customer', $customer);
		$this->view->assign('records',$fRecords);
		
		//左侧样式是否显示高亮样式
		$recordcustomerleftcorpnav = 'yes';
		$this->view->assign('recordcustomerleftcorpnav', $recordcustomerleftcorpnav);
		
		$this->view->display('customer/record');
	}
}
