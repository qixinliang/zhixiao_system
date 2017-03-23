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

	private function _exportSelectedTree($tree,$deep = 0,$pid){
		static $html = '';
    	foreach ($tree as $k => $v) {
        	$tmpName = sprintf("%s%s", str_repeat('——', $deep), $v['department_name']);
			/*
			if($v['department_id'] == $pid){
				$html .= "<option value=$k selected=\"selected\">" . $tmpName . "</option>";
			}else{
				$html .= "<option value=$k>" . $tmpName . "</option>";
			}*/
            $html .= '<option value='.$k;
            if($v['department_id'] == $pid){
                $html.=' selected="selected" ';
            }
            $html .= '>';
            $html .= $tmpName . '</option>';

        	if (isset($v['son']) && !empty($v['son'])) {
            	$this->_exportSelectedTree($v['son'], $deep + 1,$pid);
        	}
		}
		return $html;
	}
	
	/******************************************************/

	public function run(){
        $list = $this->departmentService->getDepartmentList();
		
		/*
		 *list2的数组下标跟对应的
         *department_id是一致的，这样才能创建树
		 */
		$list2 = $this->departmentService->getDepartmentList2();	
		if(!isset($list2) || empty($list2)){
    		exit(json_encode(array('status' => -1, 'message' => '未找到任何部门!')));
		}
		
		//必须要用tree2函数，非tree函数，可以调试看出数组下标的区别。
		$tree2 = $this->_generateTree2($list2);
		$html  = $this->_exportTree($tree2);
        $this->view->assign('list', $list);
        $this->view->assign('html', $html);
        $this->view->display("department/run");
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
		$pNode = $this->departmentService->getParentNodeById($departmentId);
		if(!isset($pNode) || empty($pNode)){
            exit(json_encode(array('status' => 0, 'message' => '未发现上级节点！')));
		}
		$id 	= $pNode['department_id'];
        $list2 	= $this->departmentService->getDepartmentList2();
        $tree2 	= $this->_generateTree2($list2);
        //$html = $this->_exportTree($tree2);
        $html 	= $this->_exportSelectedTree($tree2,$id);

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
