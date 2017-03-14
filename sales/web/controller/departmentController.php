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
	
	/***************************树**************************/
	private function _generateTree($items){
    	$tree = array();
    	foreach($items as $item){
        	if(isset($items[$item['p_dpt_id']])){
            	$items[$item['p_dpt_id']]['son'][] = &$items[$item['department_id']];
        	}else{
            	$tree[] = &$items[$item['department_id']];
        	}
    	}
    	return $tree;
	}

	private function _generateTree2($items){
    	foreach($items as $item)
        	$items[$item['p_dpt_id']]['son'][$item['department_id']] = &$items[$item['department_id']];
    	return isset($items[0]['son']) ? $items[0]['son'] : array();
	}

	private function _getTreeData($tree){
		static $html;
		$html .= "<ul>";
		foreach($tree as $k => $v){
			var_dump($k);
			$html .= "<li><a href=\"#\" ref=$k>" . $v['name'] . "</a></li>";
			if(isset($v['son'])){
				$this->_getTreeData($v['son']);
			}
		}
		$html .= "</ul>";
		return $html;
	}

	private function _exportTree($tree,$deep = 0){
		static $html = '';
    	foreach ($tree as $k => $v) {
        	$tmpName = sprintf("%s%s", str_repeat('——', $deep), $v['department_name']);
			$html .= "<option value=$k>" . $tmpName . "</option>";
        	if (isset($v['son']) && !empty($v['son'])) {
            	$this->_exportTree($v['son'], $deep + 1);
        	}
		}
		return $html;
	}
	
	/******************************************************/

	public function run(){
		//从数据库中直接取出格式化完成的树
		/*
		$tree = $this->departmentService->getDepartmentTree();
		print_r($tree);
		echo('<pre>');
		die;
		*/
        $list = $this->departmentService->getDepartmentList();
		
		//list2的数组下标跟对应的department_id是一致的，这样才能创建树。
		$list2 = $this->departmentService->getDepartmentList2();	
		$tree2 = $this->_generateTree2($list2);
		$html = $this->_exportTree($tree2);
        $this->view->assign('list', $list);
        $this->view->assign('html', $html);
        $this->view->display("department/run"); //使用模板
	}
	
	//默认是树形的部门树结构展示，代码中需要增加节点的合并，删除等操作。
	public function run2(){
		//$this->authService->checkauth("1017");//权限检测目前这里暂时注释
		//默认显示部门树结构。


		//1.先取出现有系统中的所有数据。
		$lists = $this->departmentService->getDepartmentList();
		//转换成tree
		$toTrees = $this->_generateTree2($lists);
		var_dump($toTrees);

		//$items = $this->departmentService->getDepartmentTree();
		exit;
		$items = array(
    		1 => array('department_id' => 1, 'p_dpt_id' => 0, 'name' => '安徽省'),
    		2 => array('department_id' => 2, 'p_dpt_id' => 0, 'name' => '浙江省'),
    		3 => array('department_id' => 3, 'p_dpt_id' => 1, 'name' => '合肥市'),
    		4 => array('department_id' => 4, 'p_dpt_id' => 3, 'name' => '长丰县'),
    		5 => array('department_id' => 5, 'p_dpt_id' => 1, 'name' => '安庆市'),
		);
		$tree = $this->_generateTree2($items);
		var_dump($tree);
		$treeData = $this->_getTreeData($tree);
		//var_dump($treeData);
        $list = $this->departmentService->getDepartmentList();
        $this->view->assign('list', $list);
        $this->view->assign('tree', $treeData);
        $this->view->display("department/run"); //使用模板

	}

	public function add(){
        $list2 = $this->departmentService->getDepartmentList2();
        $tree2 = $this->_generateTree2($list2);
        $html = $this->_exportTree($tree2);
        $this->view->assign('html', $html);
		$this->view->assign('action_name','添加');
        $this->view->assign('action', 'add');
        $this->view->display("department/add"); //使用模板
	}
	
	public function add2(){
		$departmentInfo = $this->departmentService->getDefaultDepartmentDict();
		$this->view->assign('action_name','添加');
        $this->view->assign('action', 'add');
		$this->view->assign('department_info',$departmentInfo);
        $this->view->display("department/add"); //使用模板
	}

	public function addSave(){
		$data = array(
			'department_name' => $this->controller->get_gp('name'),
			'p_dpt_id'		  => $this->controller->get_gp('p_dpt_id'),
			'status'		  => 0,//正常
			'create_time'	  => time(),
			'update_time'	  => time()
		);
        $ret = $this->departmentService->addSave($data);
        if($ret){
            exit(json_encode(array('status' => 1, 'message' => '部门信息添加成功!')));
        }else{
            exit(json_encode(array('status' => 0, 'message' => '部门信息添加失败!')));
        }
	}

	public function edit(){
		$departmentId = $this->controller->get_gp('department_id');
		$data = $this->departmentService->getDepartmentInfo($departmentId);
		if(!isset($data) || empty($data)){
            exit(json_encode(array('status' => 0, 'message' => '未发现此条数据！')));
		}
		//TODO 列举出现有系统出的所有部门信息。
        $list2 = $this->departmentService->getDepartmentList2();
        $tree2 = $this->_generateTree2($list2);
        $html = $this->_exportTree($tree2);
        $this->view->assign('html', $html);
		
		$this->view->assign('data',$data);
		$this->view->assign('action','editSave');
		$this->view->display("department/edit");

	}
	
	public function edit2(){
		$departmentId = $this->controller->get_gp('department_id');
		$data = $this->departmentService->getDepartmentInfo($departmentId);
		if(!isset($data) || empty($data)){
            exit(json_encode(array('status' => 0, 'message' => '未发现此条数据！')));
		}
		//TODO 列举出现有系统出的所有部门信息。
		//$allData = $this->departmentService->getDepartmentList();
		
		$departmentInfo = $this->departmentService->getDefaultDepartmentDict();
		$this->view->assign('department_info',$departmentInfo);
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
