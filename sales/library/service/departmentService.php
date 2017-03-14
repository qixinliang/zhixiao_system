<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * 部门管理服务层 by qixinliang 2017.3.13
 */
class departmentService extends Service{
    
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
}
