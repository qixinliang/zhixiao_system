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
		/*
		 * @获取部门
		 */
		$departmentService = InitPHP::getService("department"); //上级列表
		$list2 = $departmentService->getDepartmentList2();
        $tree2 = $this->_generateTree2($list2);
        $html = $this->_exportTree($tree2,$uDepartment);
        $this->view->assign('html', $html);
		/*
		 * @获取角色
		 */
        $adminGroupService = InitPHP::getService("adminGroup"); //获取角色
		$user_group = $adminGroupService->adminList();
		$this->view->assign('user_group', $user_group);
        $this->view->assign('page',$page);
        $this->view->assign('page_html', $pageHtml);
		$this->view->assign('list',$list);
		$this->view->display('customer/run');
	}
	private function _generateTree2($items){
	    foreach($items as $item)
	        $items[$item['p_dpt_id']]['son'][$item['department_id']] = &$items[$item['department_id']];
	    return isset($items[0]['son']) ? $items[0]['son'] : array();
	}
	
	private function _exportTree($tree,$department_id=0,$deep = 0){
	    static $html = '<option value="0">请选择</option>';
	    foreach ($tree as $k => $v) {
	        $tmpName = sprintf("%s%s", str_repeat('——', $deep), $v['department_name']);
	        $html .= '<option value='.$k;
	        if($v['department_id']==$department_id){
	            $html.=' selected="selected" ';
	        }
	        $html .= '>';
	        $html .= $tmpName . '</option>';
	
	        if (isset($v['son']) && !empty($v['son'])) {
	            $this->_exportTree($v['son'],$department_id, $deep + 1);
	        }
	    }
	    return $html;
	}
}
