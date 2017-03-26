<?php
if(!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * @业绩管理
 */
class achievementController extends baseController{
	
	public $adminService 		= NULL;
	public $departmentService   = NULL;
	public $bmyjmxService 		= NULL;

	public $initphp_list = array('total');
	public function __construct(){
		parent::__construct();
		$this->adminService 		= InitPHP::getService('admin');
		$this->departmentService    = InitPHP::getService('department');
		$this->bmyjmxService        = InitPHP::getService("bmyjmx");
	}

	/*
     *@业绩统计
	 */
	public function total(){
		//获取当前登陆用户.
		$userInfo = $this->adminService->current_user();
		if(!isset($userInfo)){
 			exit(json_encode(array('status' => -1,'message' => 'Current User not found！')));
		}
		
		$uid 			= $userInfo['id']; //admin表主键
		$departmentId 	= $userInfo['department_id'];//部门ID
		$roleId 		= $userInfo['gid']; //角色ID
		
		//根据用户的部门ID获取子级部门
		$departments1 = $this->departmentService->getChilds($departmentId);

		//根据部门ID获取它的所有子部门
		$departments = $this->departmentService->getChildNodes($departmentId);
		if(!isset($departments) || empty($departments)){
 			exit(json_encode(array('status' => -1,'message' => 'Departments NULL！')));
		}
		echo('<pre>');
		print_r($departments);
		echo('</pre>');
		echo("xxxxxxxxxxxxxxxxxxxxxxxxxx");

		//获取部门下的用户业绩明细
		$userData     = $this->bmyjmxService->getUserDataList($departments1); 
		echo('<pre>');
		print_r($userData);
		echo('</pre>');
		echo('111111111---------------');

		//统计计算
		$finalData = $this->calculate($departments,$userData);
		echo('<pre>');
		print_r($finalData);
		echo('</pre>');
		die('111111111');
		
		//先模拟数据测试动态表格创建
		$departments = json_encode(array(
			4 => array(
				'department_id' => 4,
				'p_dpt_id' => 2,
				'department_name' => '承德一区',
				'son' => array(
					'7' => array(
						'department_id' => 7,
						'p_dpt_id' => 4,
						'department_name' => '兴隆县',
					)
				)
			),
			5 => array(
				'department_id' => 5, 
				'p_dpt_id' => 2,
				'department_name' => '承德二区',
			),
			6 => array(
				'department_id' => 6, 
				'p_dpt_id' => 2,
				'department_name' => '承德三区',
			),
		));
		$this->view->assign('departments',$departments);
		$this->view->display('achievement/total');
	}

	//传递过来一个部门树，及用户的业绩明细，
	//将用户的业绩明细，加到最底层的节点上
	public function calculate(&$departments,&$userData){
		static $rujin = 0; //入金
		static $zhebiao = 0; //折标
		static $huikuan = 0; //回款
		
		foreach($departments as $k => $v){
			foreach($userData as $k1 => $v1){
				$did  = $v['department_id'];//部门树中的ID
				$did1 = $v1['department_id']; //用户信息中的部门ID
				var_dump($did);
				
				if($did == $did1){
					//取出所有同一部门的数据做累加
					$rujin += $v1['zonge'];
					$zhebiao += $v1['nianhuan'];
					$huikuan += $v1['huikuan'];
					echo("!!!!!!!!!!!!!!!!!!!!!!");
					$departments[$k]['rujin'] = $rujin;
					$departments[$k]['zhebiao'] = $zhebiao;
					$departments[$k]['huikuan'] = $huikuan;
				}
			}
			if(isset($v['son'])){
				$this->calculate($v['son'],$userData);
			}
		}
		return $departments;
	}
}
