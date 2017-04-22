<?php
if(!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * 部门管理控制器 by qixinliang 2017.3.13
 */

class departmentController extends baseController{
	public $initphp_list = array('add','addSave','edit','editSave','del');
	
	public function __construct(){
		parent::__construct();
		$this->adminService      = InitPHP::getService('admin');
		$this->authService 		 = InitPHP::getService("auth");
		$this->departmentService = InitPHP::getService('department');
	}
	
	public function run(){
		$this->authService->checkauth('1001');
	    $userinfo = $this->adminService->current_user();
		/*
		 *list2的数组下标跟对应的
         *department_id是一致的，这样才能创建树
		 */
		$list2 = $this->departmentService->getDepartmentList2();
		if(!isset($list2) || empty($list2)){
    		exit(json_encode(array('status' => -1, 'message' => 'empty departments!')));
		}
		
		//必须要用tree2函数，非tree函数，可以调试看出数组下标的区别。
		$tree2 = $this->departmentService->generateTree2($list2);
		$html  = $this->departmentService->exportTree($tree2);

		//取出部门人数
		foreach($list2 as $k => $v){
			$did = $v['department_id'];
			$tmpRet = $this->adminService->getUserCount($did);
			$cnt = 0;
			if(isset($tmpRet) && !empty($tmpRet)){
				$cnt = $tmpRet['count']; 
			}
			$v['user_cnt'] = $cnt;
			$list2[$k] = $v;
		}

		$listJson = json_encode($list2);
		$this->view->assign('gid', $userinfo['gid']);
        $this->view->assign('list', $list2);
        $this->view->assign('html', $html);
        $this->view->assign('list_json', $listJson);
        
        $department='yes';//默认加样式
        $this->view->assign('department', $department);
        //左侧样式是否显示高亮样式
        $departmentleftcorpnav = 'yes';
        $this->view->assign('departmentleftcorpnav', $departmentleftcorpnav);
        
        $this->view->display("department/run");
        
	}

	public function add(){
		$this->authService->checkauth('1002');
		//生成目录树的下拉框展示。
        $list2 = $this->departmentService->getDepartmentList2();
        $tree2 = $this->departmentService->generateTree2($list2);
        $html  = $this->departmentService->exportTree($tree2);

        $this->view->assign('html', $html);
		$this->view->assign('action_name','添加');
        $this->view->assign('action', 'add');
        
        $department='yes';//默认加样式
        $this->view->assign('department', $department);
        
        //左侧样式是否显示高亮样式
        $departmentleftcorpnav = 'yes';
        $this->view->assign('departmentleftcorpnav', $departmentleftcorpnav);
        
        $this->view->display("department/add"); //使用模板
	}
	
	public function addSave(){
		$this->authService->checkauth('1003');
		//根据父节点添加子节点...
		$pid 	= $this->controller->get_gp('p_dpt_id');
		$dName 	= $this->controller->get_gp('name'); 
		$ret    = $this->departmentService->addNodes($pid,$dName);
        if($ret){
            exit(json_encode(array('status' => 1, 'message' => '部门信息添加成功!')));
        }else{
            exit(json_encode(array('status' => 0, 'message' => '部门信息添加失败!')));
        }
	}

	public function edit(){
		$this->authService->checkauth('1004');
		$departmentId = $this->controller->get_gp('department_id');
		$data 		  = $this->departmentService->getDepartmentInfo($departmentId);
		if(!isset($data) || empty($data)){
            exit(json_encode(array('status' => 0, 'message' => '未发现此条数据！')));
		}
		//获取出上级部门ID
		$pNode  = $this->departmentService->getParentNodeById($departmentId);
		if(!isset($pNode) || empty($pNode)){
            exit(json_encode(array('status' => 0, 'message' => '未发现上级节点！')));
		}
		$id 	= $pNode['department_id'];
        $list2 	= $this->departmentService->getDepartmentList2();
        $tree2 	= $this->departmentService->generateTree2($list2);
        $html 	= $this->departmentService->exportSelectedTree($tree2,$id);
        
        $department='yes';//默认加样式
        $this->view->assign('department', $department);
        
        //左侧样式是否显示高亮样式
        $departmentleftcorpnav = 'yes';
        $this->view->assign('departmentleftcorpnav', $departmentleftcorpnav);
        $this->view->assign('html', $html);
		$this->view->assign('data',$data);
		$this->view->assign('action','editSave');
		$this->view->display("department/edit");
	}
	
	public function editSave(){
		$this->authService->checkauth('1005');
		//当前部门ID
		$id = $this->controller->get_gp('department_id');
		//当前部门名字
		$dName 	= $this->controller->get_gp('name');
		//上级部门ID
		$pid 	= $this->controller->get_gp('p_dpt_id');

		$data = array(
			'department_id' => $id,
			'department_name' => $dName,
			'p_dpt_id' => $pid,
			'update_time' => time(),
		);

        $arr = $this->departmentService->editSave($data);
        if($arr){
            exit(json_encode(array('status' => 1, 'message' => '部门信息修改成功!')));
        }
	}

	public function del(){
		$this->authService->checkauth('1006');
		$departmentId = $this->controller->get_gp('department_id');
		$ret = $this->departmentService->del($departmentId);
        if($ret){
            exit(json_encode(array('status' => 1, 'message' => '部门信息删除成功!')));
        }else{
            exit(json_encode(array('status' => -1, 'message' => '部门信息删除失败!')));
		}
	}
}
