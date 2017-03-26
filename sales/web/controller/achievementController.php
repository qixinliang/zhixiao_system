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
		
		//销售1.1部
		//$did = 11;
		//$ret = $this->bmyjmxService->getInvestInfoByDepartmentId($did);
		
		//获取所有的部门列表
		$dpts = $this->departmentService->getDepartmentList2();
		echo('<pre>');
		print_r($dpts);
		echo('</pre>');

		//$v必须要用引用...必须要用
		foreach($dpts as $k => &$v){
			//$v['investor_info'] = $this->bmyjmxService->getInvestInfoByDepartmentId($k);
			$tmpInfo = $this->bmyjmxService->getInvestInfoByDepartmentId($k);
			if(isset($tmpInfo) && !empty($tmpInfo)){
				$rujin = 0;
				$zhebiao = 0;
				$huikuan = 0;
				$cnt = 0;
				foreach($tmpInfo as $k1 => $v1){
					$cnt++;//投资明细数
            		$rujin += $v1['zonge'];
            		$zhebiao += $v1['nianhuan'];
            		$huikuan += $v1['huikuan'];
				}
				$v['invest_info']['rujin'] = $rujin;
				$v['invest_info']['zhebiao'] = $zhebiao;
				$v['invest_info']['huikuan'] = $huikuan;
				$v['invest_info']['cnt'] = $cnt;
			}else{
				$v['invest_info']['rujin'] = 0;
				$v['invest_info']['zhebiao'] = 0;
				$v['invest_info']['huikuan'] = 0;
				$v['invest_info']['cnt'] = 0; 
			}
		}
		//把$dpts变成一种带son级联形式的，便于统计
		//FIXME 这地方就可以循环上面的列表，做计算了。。。考虑下
		$dpts = $this->departmentService->generateTree2($dpts);
		

		//妥妥滴，要传入一个部门ID,把刚才的dpts的结果export出来,其中相同的部门ID下的
		//投资信息要做累加
		$tree = $this->cal($dpts);
		echo("---------------zuizhongjieguo");
		echo('<pre>');
		print_r($tree);
		echo('</pre>');
		die("22222222222");
		

		echo('<pre>');
		print_r($ret);
		echo('</pre>');
		$up = $this->bmyjmxService->up($did);
		echo('<pre>');
		print_r($up);
		echo('</pre>');
		die("1111111111111111111111");
		
		//////////////////
		
		$departmentList = $this->departmentService->getChilds($departmentId);


		/*
		$userData       = $this->bmyjmxService->getUserDataList($departmentList); 
	
		echo('<pre>');
		print_r($userData);
		echo('</pre>');
		die("1111");*/




		//根据用户的部门ID获取子级部门
		$departments = $this->departmentService->getChildNodes($departmentId);
		if(!isset($departments) || empty($departments)){
 			exit(json_encode(array('status' => -1,'message' => 'Departments NULL！')));
		}
		echo("--------------部门树-------------");
		echo('<pre>');
		print_r($departments);
		echo('</pre>');

		//获取部门下的用户业绩明细
		$userData     = $this->bmyjmxService->getUserDataList($departmentList); 
		echo("--------------用户业绩明细-------------");
		echo('<pre>');
		print_r($userData);
		echo('</pre>');

		//统计计算
		$finalData = $this->calculate($departments,$userData);
		echo("--------------最终数据-------------");
		echo('<pre>');
		print_r($finalData);
		echo('</pre>');
		die('ok');
		$finalData = json_encode($finalData);

		$this->view->assign('departments',$departments);
		$this->view->assign('final_data',$finalData);
		$this->view->display('achievement/total');
	}

	//根据一个部门ID获取它的投资明细
	//假设是最小颗粒度
	public function getInvestInfoByDepartmentId($did){
		$ret = $this->bmyjmxService->getInvestInfoByDepartmentId($did);
		return $ret;
	}

	//传递过来一个部门树，及用户的业绩明细，
	//将用户的业绩明细，加到相同部门的节点上
	public function calculate2(&$departments,&$userData){
		foreach($departments as $k => $v){
			$rujin   = 0; //入金
			$zhebiao = 0; //折标
			$huikuan = 0; //回款
			$cnt     = 0; //用于计算该部门下的用户数
			if(isset($v['son'])){
				$this->calculate($v['son'],$userData);
			}
			foreach($userData as $k1 => $v1){
				$did  = $v['department_id'];//部门树中的ID
				$did1 = $v1['department_id']; //用户信息中的部门ID
				var_dump($did);
				if($did == $did1){
					//取出同一部门的数据做累加
					$rujin 	 += $v1['zonge'];
					$zhebiao += $v1['nianhuan'];
					$huikuan += $v1['huikuan'];
					$cnt++;
					echo("次数:$cnt...");
					if($k == 9){
						echo("下标为9");
						echo('<pre>');
						print_r($departments);
						echo('</pre>');
					}

					$departments[$k]['rujin']   = $rujin;
					$departments[$k]['zhebiao'] = $zhebiao;
					$departments[$k]['huikuan'] = $huikuan;
					$departments[$k]['cnt']     = $cnt;
				}
			}
		}
		return $departments;
	}
	
	//传入一个部门ID,计算最终结果
	public function cal(&$tree){
		static $selfRujin = 0;
		static $selfZhebiao = 0;
		static $selfHuikuan = 0;
		static $selfCnt = 0;
		foreach($tree as $k => &$v){
			$selfRujin += $v['invest_info']['rujin'];
			$selfZhebiao += $v['invest_info']['zhebiao'];
			$selfHuikuan += $v['invest_info']['huikuan'];
			$selfCnt += $v['invest_info']['cnt'];
			if(isset($v['son'])){
				foreach($v['son'] as $k1 => $v1){
					$v['invest_info']['rujin'] += $v1['invest_info']['rujin'];
					$v['invest_info']['zhebiao'] += $v1['invest_info']['zhebiao'];
					$v['invest_info']['huikuan'] += $v1['invest_info']['huikuan'];
					$v['invest_info']['cnt'] += $v1['invest_info']['cnt'];
				}
				$this->cal($v['son']);
			}
		}
		return $tree;
	}
}
