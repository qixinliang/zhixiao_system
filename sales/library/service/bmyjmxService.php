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
    public function getDepartmentUser($department_id,$where=''){
        return $this->bmyjmxsDao->getDepartmentUser($department_id,$where);
    }
    
    /**
     * 拼接检索条件，和url地址
     * @param type $department_id
     * @param type $username
     * @param type $start_date
     * @param type $end_date
     * @return type
     */
    public function arrange_where_url($department_id,$username,$start_date,$end_date){
        $where = ' ';
        //分页地址
        $url = 'bmyjmx/run';
        $excelUrl = 'bmyjmx/createExcel';
        if($department_id!=''){
            $url=$url.'/department_id/'.$department_id;
            $excelUrl=$excelUrl.'/department_id/'.$department_id;
            $where.= ' and d.department_id = "'.$department_id.'"';
        }
        if($username!=''){
            $url=$url.'/username/'.$username;
            $excelUrl=$excelUrl.'/username/'.$username;
            $where.= " and z.UsrName = '$username'";
        }
        if(!empty($start_date) && !empty($end_date)){
            $url=$url.'/start_date/'.$start_date.'end_data/'.$end_date;
            $excelUrl=$excelUrl.'/start_date/'.$start_date.'end_data/'.$end_date;
        }
        
        return array('url'=>$url,'where'=>$where,'excelUrl'=>$excelUrl);
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
     * @param unknown $my_department_lsit
     * @param string $where     查询用户的检索条件
     * @param unknown $start_date 根据时间检索，开始时间
     * @param unknown $end_date   结束时间
     * @return Ambigous <unknown, type>
     */
    public function getUserDataList($my_department_lsit,$where='',$start_date='',$end_date=''){
        //循环所有的部门，查询所有部门下的user
        $user_array = array();
        foreach($my_department_lsit as $k=>$v){
            //获取用户信息，并赋值给user_array
            $user = $this->getDepartmentUser($v['department_id'],$where);//getDepartmentUser
            if(!empty($user) && isset($user)){
                $user_array[] = $user;
            }
        }
        //根据用户，循环查询所有的业绩明细
        foreach($user_array as $k=>$val){
            foreach ($val as $k1=>$val1){
                $userYeji = $this->myResultsService->getTopranking($val1['id'],$start_date,$end_date);
                $val1['yaoqingrencount'] = $userYeji['yaoqingrencount'];
                $val1['zonge'] = $userYeji['zonge'];
                $val1['nianhuan'] = $userYeji['nianhuan'];
                $val1['huikuan'] = $userYeji['huikuan'];
                $user_data[] = $val1;
            }
        }
        return $user_data;
    }

	//获取一个部门下的投资明细
	public function getInvestInfoByDepartmentId($did,$start_date=null,$end_date=null){
		$finalArr = array();
		//获取部门下的用户
    	$users = $this->getDepartmentUser($did);
		if(isset($users) && !empty($users)){
			foreach($users as $k => $v){
                $userYeji = $this->myResultsService->getTopranking($v['id'],$start_date,$end_date);
                $v['yaoqingrencount'] = $userYeji['yaoqingrencount'];
                $v['zonge'] = $userYeji['zonge'];
                $v['nianhuan'] = $userYeji['nianhuan'];
                $v['huikuan'] = $userYeji['huikuan'];
                $finalArr[] = $v;
			}
		}
		return $finalArr;
	}
	
	public function up($did){
		$data = $this->getInvestInfoByDepartmentId($did);
		echo('<pre>');
		print_r($data);
		echo('</pre>');

		//把统一部门下的金额计算
		$rujin = 0;
		$zhebiao = 0;
		$huikuan = 0;
		$cnt = 0;
		$tmp = array();
		foreach($data as $k => $v){
            $cnt++;
            $rujin += $v['zonge'];
            $zhebiao += $v['nianhuan'];
            $huikuan += $v['huikuan'];
		}
		$tmp['rujin'] = $rujin;
		$tmp['zhebiao'] = $zhebiao;
		$tmp['huikuan'] = $huikuan;
		$tmp['cnt'] = $cnt;
		
		//要把这些数据加入到上级节点里
		//1.获取上级部门
		$pDepartment = $this->departmentDao->getParentNodeById($did);
		$res = array();
		while($pDepartment){
			$did = $pDepartment['department_id'];
			var_dump($did);
			$pDepartment['invest_info'] = $tmp;
			$res[] = $pDepartment;
			$pDepartment = $this->departmentDao->getParentNodeById($did);
				echo('<pre>');
			print_r($res);
				echo('</pre>');
			
		}
		/*
		if(isset($pDepartment) && !empty($pDepartment)){
			$departmentId = $pDepartment['department_id'];
			var_dump($departmentId);
			$pDepartment['invest_info'] = $tmp;
		}*/
		var_dump($pDepartment);
		die('-1111');
	}

	//把最底层的用户投资数据，往上传递到父节点
	public function up2($did,&$output = array()){
		$tmp= array();
		//取到最底层的数据
		$data = $this->getInvestInfoByDepartmentId($did);
		//获取上级部门
		$pDepartment = $this->departmentDao->getParentNodeById($did);
		if(isset($pDepartment) && !empty($pDepartment)){
			$rujin = 0;
			$zhebiao = 0;
			$huikuan = 0;
			$cnt = 0;
			foreach($data as $k => $v){
				$cnt++;
				$rujin += $v['zonge'];
				$zhebiao += $v['nianhuan'];
				$huikuan += $v['huikuan'];
				$pDepartment['son'][] = $v;
			}

			//当前父级节点的部门ID
			$departmentId = $pDepartment['department_id'];
			$pDepartment['invest_info']['rujin'] = $rujin;
			$pDepartment['invest_info']['zhebiao'] = $zhebiao;
			$pDepartment['invest_info']['huikuan'] = $huikuan;
			$pDepartment['invest_info']['cnt'] = $cnt;
			$tmp[$departmentId] = $pDepartment;
			$output = array_merge($tmp,$output);
			$this->up($departmentId,$output);
			//如果当前部门ID与根节点或者是传入的统计参数中的部门ID相等
			var_dump($departmentId);
			if($departmentId == 9){
				echo('<pre>');
				print_r($output);
				echo('</pre>');
				return $output;
			}
		}
	}
}
