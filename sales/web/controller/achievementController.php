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
		
		//获取当前登录用户部门下级列表
		$dpts = $this->departmentService->getChilds($departmentId);
	    foreach ($dpts as $key=>$val){
	        
	        //目前取得的数据是  个人业绩  不包含客户业绩   需要修改 -------------------------------------
			$tmpInfo = $this->bmyjmxService->getInvestInfoByDepartmentId($val['department_id'],'2016-10');
			$rujin = 0;
			$zhebiao = 0;
			$huikuan = 0;
			$cnt = 0;
			//本部门投资明细
			foreach($tmpInfo as $k1 => $v1){
			    $cnt++;//投资明细数
			    $val['benbu_rujin'] += $v1['zonge'];
			    $val['benbu_zhebiao'] += $v1['nianhuan'];
			    $val['benbu_huikuan'] += $v1['huikuan'];
			    $val['benbu_cnt'] = $cnt++;
			}
			$dpts[$key]=$val;
			
			$dpts[$key]['heji'] =  $this->getXiaJiBuMenShuJu($val['department_id']);

	    }

	    print_r($dpts);
exit;

		$this->view->assign('departments',$departments);
		$this->view->assign('final_data',$finalData);
		$this->view->display('achievement/total');
	}

	//递归获取下级数据
	public function getXiaJiBuMenShuJu($did){
	    echo $did;
	    $dpts = $this->departmentService->getChilds($did);
	    $hejirujin = 0;
	    foreach ($dpts as $key=>$val){
	        //目前取得的数据是  个人业绩  不包含客户业绩   需要修改 -------------------------------------
	        $tmpInfo = $this->bmyjmxService->getInvestInfoByDepartmentId($val['department_id'],'2016-10');
	        $rujin = 0;
	        $zhebiao = 0;
	        $huikuan = 0;
	        $cnt = 0;
	        //本部门投资明细
	        foreach($tmpInfo as $k1 => $v1){
	            $hejirujin += $v1['zonge'];
	        }
	        	
	        //$this->getXiaJiBuMenShuJu($did);
	    }
	    return $hejirujin;
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
