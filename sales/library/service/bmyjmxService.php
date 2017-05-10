<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 部门业绩明细，业务层
 * @author cxd
 */
class bmyjmxService extends Service
{
    
    public function __construct()
    {
        parent::__construct();
        $this->bmyjmxsDao = InitPHP::getDao("bmyjmx");
        $this->myResultsService = InitPHP::getService("myResults");
		$this->departmentDao = InitPHP::getDao('department');
		$this->adminDao = InitPHP::getDao('admin');
    }
    
    /**
     * 获取所有的部门列表
     * @param type $department_id
     * @return type
     */
    public function getDepartmentList($department_id){
        return $this->bmyjmxsDao->getDepartmentList($department_id);
    }
    
    /**
     * 获取部门用户
     * @param type $department_id
     * @param type $where
     * @return type
     */
    public function getDepartmentUser($department_id=0,$where=''){
        return $this->bmyjmxsDao->getDepartmentUser($department_id,$where);
    }
    
    /**
     * 根据部门id，获取我的部门数据
     * @param type $department_id
     * @return type
     */
    public function getMyDepartment($department_id){
        return $this->bmyjmxsDao->getMyDepartment($department_id);
    }
    
    /**
     * 获取用户列表
     * @param unknown $sonDepartment
     * @param string $where     查询用户的检索条件
     * @param unknown $start_date 根据时间检索，开始时间
     * @param unknown $end_date   结束时间
     * @return Ambigous <unknown, type>
     */
    public function getDepartmentUserDetail($userId,$sonDepartment,$where=null,$startDate=null,$endDate=null){
        //循环所有的部门，查询所有部门下的user
		//xd added
        if(empty($userId)){
           return array();
        }

        $departmentUser = $this->getSonDepartmentUserList($sonDepartment,$where);
        
        /**
         * 1.判断当前登录用户gid，如果gid=9，说明是团队经理，需要统计当前团队的所有销售人员
         * 2.如果>9说明是团队经理以上级别，统计当前登录用户的个人业绩，和其部门的业绩累加
         */
        //获取登录用户的信息列表
        $userData = $this->getUserInfo($userId,$where);

        //判断当前登录用户角色，根据当前角色，如果当前用户是团队经理，应该把当前所有团队的数据都获取出来
        $userData = $this->getUserYeji($userData,$where,$startDate,$endDate);

        //根据用户列表，查询所有用户的业绩明细
        $departmentUserData = $this->getResultsDetail($departmentUser,$startDate,$endDate);
        
        //将登录用户的业绩明细，和部门用户的用户明细列表合并成一个数组
        $departmentUserDatas = array_merge($userData,$departmentUserData);
        
        return $departmentUserDatas;
    }

	//获取一个部门下的投资明细
	public function getInvestInfoByDepartmentId($did,$start_date=null,$end_date=null){
		$finalArr = array();
		//获取部门下的用户
    	$users = $this->getDepartmentUser($did);
		if(isset($users) && !empty($users)){
			foreach($users as $k => $v){
                $userYeji = $this->myResultsService->getSummaryRanking(intval($v['id']),$start_date,$end_date);
                $v['yaoqingrencount'] = $userYeji['yaoqingrencount'];
                $v['zonge'] = $userYeji['zonge'];
                $v['nianhuan'] = $userYeji['nianhuan'];
                $v['huikuan'] = $userYeji['huikuan'];
                $finalArr[] = $v;
			}
		}
		return $finalArr;
	}
	
	/**
	 * 获取登录用户的详细信息
	 * @param unknown $userId
	 * @param string $where
	 */
	public function getUserInfo($userId,$where=null){
	    return $this->bmyjmxsDao->getUserInfo($userId,$where);
	}
	
	public function getUserYeji($userData,$where=null, $startDate=null, $endDate=null){
	    
	    if (!is_array($userData)) return $userData = array();
	    if($userData[0]['gid']=='9'){
	        //查询当前部门下所有的人员
	        $departmentList = $this->bmyjmxsDao->getDepartmentUser($userData[0]['department_id'],$where);
	        if(is_array($departmentList)){
	            $departmentList = $this->getResultsDetail($departmentList, $startDate, $endDate);
	        }
	    }else{
	        //如果gid不等于9查询当前登录用户的业绩
	        $departmentList = $this->getResultsDetail($userData, $startDate, $endDate);
	    }
	    return $departmentList;
	}
	
	/**
	 * 根据用户列表，统计用户的业绩
	 * @param unknown $departmentUser
	 * @param string $where
	 * @param string $startDate
	 * @param string $endDate
	 * @return array $departmentUserData
	 */
	public function getResultsDetail($departmentUser, $startDate=null, $endDate=null){
	    
	    $departmentUserData = array();
	    foreach($departmentUser as $k=>$val){
	            $userYeji = $this->myResultsService->getSummaryRanking(intval($val['id']),$startDate,$endDate);
	            $val['yaoqingrencount'] = $userYeji['yaoqingrencount'];
	            $val['zonge'] = $userYeji['zonge'];
	            $val['nianhuan'] = $userYeji['nianhuan'];
	            $val['huikuan'] = $userYeji['huikuan'];
	            $departmentUserData[] = $val;
	    }
	    return $departmentUserData;
	}
	
	/**
	 * 根据当前子部门，循环查询出子部门所有的用户
	 * @param unknown $sonDepartment
	 * @param unknown $where
	 */
	public function getSonDepartmentUserList($sonDepartment,$where){
	    $departmentUser = array();
	    if(empty($sonDepartment)){
	        return $departmentUser;
	    }
	    foreach($sonDepartment as $k=>$v){
	        //获取用户信息，并赋值给user_array
	        $user = $this->getDepartmentUser(intval($v['department_id']),$where);//getDepartmentUser
	        if(!empty($user) && isset($user)){
	            foreach ($user as $k1 =>$v1){
	                $departmentUser[] = $v1;
	            }
	        }
	    }
	    return $departmentUser;
	}
}
