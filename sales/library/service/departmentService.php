<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * 部门管理服务层 by qixinliang 2017.3.13
 */
class departmentService extends Service{
    
    public $department_name = '';
    
	public static $defaultDepartmentDict = array(
		1 => '中承集团',
		2 => '承德市',
		3 => '承德一区',
		4 => '承德二区',
		5 => '承德三区',
		6 => '怀来县',
	);

	private $_departmentDao = NULL;

    public function __construct(){
        parent::__construct();
        $this->_departmentDao = InitPHP::getDao("department");
    }

	public function getDefaultDepartmentDict(){
		return self::$defaultDepartmentDict;
	}

	public function getDepartmentTree(){
		return $this->_departmentDao->getDepartmentTree();
	}
    
    public function getDepartmentList(){
        return $this->_departmentDao->getDepartmentList();
    }

    public function getDepartmentList2(){
        return $this->_departmentDao->getDepartmentList2();
    }

    public function getDepartmentInfo($id){
        return $this->_departmentDao->getDepartmentInfo($id);
    }
    
    public function addSave($data){
        return $this->_departmentDao->addSave($data);
    }
    
    public function editSave($data){
        return $this->_departmentDao->editSave($data);
    }
    
    public function del($id){
        return  $this->_departmentDao->del($id);
    }

	public function addNodes($pid,$name){
		return $this->_departmentDao->addNodes($pid,$name);
	}

	public function getParentNodeById($id){
		return $this->_departmentDao->getParentNodeById($id);
	}
	
	/*
     * 从部门列表获取的数组，生成一种树结构
	 * son字段对应的数组代表了下面的子节点.
     */
	public function generateTree($items){
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

    public function generateTree2($items){
        foreach($items as $item)
            $items[$item['p_dpt_id']]['son'][$item['department_id']] = &$items[$item['department_id']];
        return isset($items[0]['son']) ? $items[0]['son'] : array();
    }

	/*
     * 递归类似array(
     *		1=>array(
	 *			'department_id' => 1,
     *			'p_dpt_id' => 0,
     *			'department_name' => 'aaa',
	 *			...
     *			son => array(
     *				3 => array(
     *					'department_id'	 => 3,
	 *					'p_dpt_id'	=> 1,
     *					...
	 *				)
	 *				4 => array(
	 *					...
	 *				)
	 *			)
	 *		2 => array(
	 *			...
	 *      )
	 *    )
	 * 生成一个select下拉框提供给view层 
     */
    public function exportTree($tree,$deep = 0){
		static $html = '<option value="0">请选择</option>';
        foreach ($tree as $k => $v) {
            $tmpName = sprintf("%s%s", str_repeat('——', $deep), $v['department_name']);
            $html .= "<option value=$k>" . $tmpName . "</option>";
            if (isset($v['son']) && !empty($v['son'])) {
                $this->exportTree($v['son'], $deep + 1);
            }
        }
        return $html;
    }

    public function exportSelectedTree($tree,$pid,$deep = 0){
        static $html = '<option value="0">请选择</option>';
        foreach ($tree as $k => $v) {
            $tmpName = sprintf("%s%s", str_repeat('——', $deep), $v['department_name']);
            if($v['department_id'] == $pid){
                $html .= "<option value=$k selected=\"selected\">" . $tmpName . "</option>";
            }else{
                $html .= "<option value=$k>" . $tmpName . "</option>";
            }
            if (isset($v['son']) && !empty($v['son'])) {
                $this->exportSelectedTree($v['son'],$pid,$deep + 1);
            }
        }
        return $html;
    }
	
	/************************************************************
	 * @copyright(c): 2017年3月24日
	 * @Author:  yuwen
	 * @Create Time: 上午10:31:36
	 * @qq:32891873
	 * @email:fuyuwen88@126.com
	 * @通过department_id获取上级部门名字和上级部门ID
	 *************************************************************/
	public function getDepartmentName($department_id){
	    $info = $this->_departmentDao->getDepartmentName($department_id);
	    if(!empty($info['p_dpt_id'])){
	        $this->department_name.=$info['department_name'].'-';
	        $this->getDepartmentName(intval($info['p_dpt_id']));
	    }
	    if(!empty($this->department_name)){
	        $arr = explode('-',$this->department_name);
	        if(is_array($arr)){
	            array_pop($arr);
	            sort($arr);
	        }
	        return implode('-', $arr);
	    }
	}

	public function getDepartmentName2($departmentId){
		$info = $this->_departmentDao->getDepartmentName($departmentId);
		if(isset($info['department_name']) && !empty($info['department_name'])){
			$departmentName = $info['department_name'];
		}else{
			$departmentName = '';
		}
		return $departmentName;
	}
}
