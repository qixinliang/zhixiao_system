<?php
if(!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * 部门管理控制器 by qixinliang 2017.3.13
 */

class departmentController extends baseController{
	public $initphp_list = array('add','addSave','edit','editSave','del');

	public function __construct(){
		parent::__construct();
		$this->departmentService = InitPHP::getService('department');
	}
	
	public function run(){
		/*
		 *list2的数组下标跟对应的
         *department_id是一致的，这样才能创建树
		 */
		$list2 = $this->departmentService->getDepartmentList2();	
		if(!isset($list2) || empty($list2)){
    		exit(json_encode(array('status' => -1, 'message' => '未找到任何部门!')));
		}
		
		//必须要用tree2函数，非tree函数，可以调试看出数组下标的区别。
		$tree2 = $this->departmentService->generateTree2($list2);
		$html  = $this->departmentService->exportTree($tree2);
        $this->view->assign('list', $list2);
        $this->view->assign('html', $html);
        $this->view->display("department/run");
	}

	public function add(){
		//生成目录树的下拉框展示。
        $list2 = $this->departmentService->getDepartmentList2();
        $tree2 = $this->departmentService->generateTree2($list2);
        $html  = $this->departmentService->exportTree($tree2);

        $this->view->assign('html', $html);
		$this->view->assign('action_name','添加');
        $this->view->assign('action', 'add');
        $this->view->display("department/add"); //使用模板
	}
	
	public function addSave(){
		/*
		$data = array(
			'department_name' => $this->controller->get_gp('name'),
			'p_dpt_id'		  => $this->controller->get_gp('p_dpt_id'),
			'status'		  => 0,//正常
			'create_time'	  => time(),
			'update_time'	  => time()
		);
        $ret 	= $this->departmentService->addSave($data);
		*/
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

        $this->view->assign('html', $html);
		$this->view->assign('data',$data);
		$this->view->assign('action','editSave');
		$this->view->display("department/edit");
	}
	
	public function editSave(){
        $arr = $this->departmentService->editSave($_POST);
        if($arr){
            exit(json_encode(array('status' => 1, 'message' => '部门信息修改成功!')));
        }
	}

	public function del(){
		$departmentId = $this->controller->get_gp('department_id');
		$ret = $this->departmentService->del($departmentId);
        if($ret == 1){
            exit(json_encode(array('status' => 1, 'message' => '部门信息删除成功!')));
        }
        if($ret == 2){
            exit(json_encode(array('status' => 2, 'message' => '无法删除!')));
        }
        if($ret == 3){
            exit(json_encode(array('status' => 3, 'message' => '越权操作!')));
        }
	}
}
