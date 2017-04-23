<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 团队统计service
 * @author aaron
*/
class teamStatService extends Service
{
    
    public function __construct()
    {
        parent::__construct();
        $this->bmyjmxService     = InitPHP::getService("bmyjmx");     //获取部门明细
        $this->departmentService = InitPHP::getService("department");
        $this->myResultsService  = InitPHP::getService("myResults");
        $this->adminService      = InitPHP::getService("admin");
        $this->TeamUtilsService  = InitPHP::getService('TeamUtils');
        $this->teamStatDao       = InitPHP::getDao("teamStat");
        $this->bmyjmxDao         = InitPHP::getDao("bmyjmx");
    }
    
    public function getTeamTop($start_time=null,$end_time=null){
        //查询出所有的部门列表
        $deparmentList = $this->departmentService->getDepartmentList();//获取所有的部门
        
        $sonDepartment = $this->TeamUtilsService->getSonDepartment($deparmentList,1);
		$this->TeamUtilsService->tree = array();
        
        //递归循环，算出所有部门的level等级
        $minDepartmentLevel = $this->minDepartment($sonDepartment);
        
        //取出level最小的部门
        $getMinDepartmentList = $this->getMinDepartmentList($sonDepartment,$minDepartmentLevel);
        
        //循环最小的部门，获取每个部门的人数，和业绩 累加，赋值给所属部门，然后排序
        $deparmentUserList = $this->getDepartmentUserList($getMinDepartmentList);
        
        //查询部门的上级部门，重新赋值
        $departmentUsers  = $this->departmentNameAssignment($deparmentUserList);
        //数组排序
        $deparmentUserList = $this->arraySort($departmentUsers);

        $list = array_slice($deparmentUserList, 0, 5);
       
        return $list;
    }
    
    /**
     * 根据部门id，统计部门的业绩，和邀请人数
     * @param unknown $departmentId 部门id
     * @param string $start_time 根据时间统计
     * @param string $end_time
     * @param number $userId 判断是否需要统计当前登录用户的业绩,如果userid有值，就统计，默认为空 
     */
    public function getDepartmentStat($departmentId=0,$start_time=null,$end_time=null,$userId=null){
        $array = array(
            'ruJinGuiMo'=>0,
            'zheBiaoJinE'=>0,
            'keHuCount'=>0,
            'departmentName'=>0
        );
        if($departmentId<=0){
            return $array;
        }
        
        $deparmentList = $this->departmentService->getDepartmentList();//获取所有的部门
        
        //获取上级部门名称
        $departmentName = $this->departmentService->getDepartmentName($departmentId,$start_time,$end_time);
        $department = explode('->', $departmentName);
        $departmentName = end($department);
        
        //获取我所有的子部门
        $sonDepartment = $this->TeamUtilsService->getSonDepartment($deparmentList,$departmentId);
        $this->TeamUtilsService->tree = array();
        
        foreach ($sonDepartment as $k1=>$v1){
            $user = $this->bmyjmxDao->getDepartmentUser(intval($v1['department_id']));
            if(!empty($user) && isset($user)){
                $departmentUser[] = $user;
            }
        }
        //查询客户业绩
        $ruJinGuiMo=0;
        $zheBiaoJinE=0;
        $keHuCount=0;
        foreach($departmentUser as $k1=>$val1){
            foreach ($val1 as $k2=>$val2){
                $userYeji = $this->myResultsService->getSummaryRanking(intval($val2['id']),$start_time,$end_time);
                $ruJinGuiMo  += $userYeji['zonge'];
                $zheBiaoJinE += $userYeji['nianhuan'];
                $keHuCount   += $userYeji['yaoqingrencount'];
            }
        }
        
        //判断，如果需要统计登录用户业绩
        if(!empty($userId) && isset($userId)){
            //获取登录用户的信息列表
            $userId = $this->adminService->GetToZiXiTongUserId($userId);
//             $userData = $this->bmyjmxService->getUserInfo($userId);
            //获取当前登录用户的业绩明细
            $res = $this->myResultsService->getSummaryRanking($userId,$start_time,$end_time);
            $ruJinGuiMo  += $res['zonge'];
            $zheBiaoJinE += $res['nianhuan'];
            $keHuCount   += $res['yaoqingrencount'];
        }
        $array =  array(
            'ruJinGuiMo'=>$ruJinGuiMo,
            'zheBiaoJinE'=>$zheBiaoJinE,
            'keHuCount'=>$keHuCount,
            'departmentName'=>$departmentName
        );
		return $array;
    }
    
    /**
     * 根据level值，获取level最小的部门列表
     * @param unknown $array
     */
    public function minDepartment($array = array()){

        if(!is_array($array)){
            return 0;
        }
        $levle1=0;
        foreach($array as $k=> $val){
            if($val['level'] > $levle1){
                $levle1 = $val['level'];
            }
        }
        return  $levle1;
    }
    
    /**
     * 根据level值，获取最小部门列表
     * @param unknown $sonDepartment
     * @param unknown $minDepartmentLevel
     */
    public function getMinDepartmentList($sonDepartment,$minDepartmentLevel){
        $res = array();
        if(!is_array($sonDepartment) || empty($minDepartmentLevel)){
            return $res;
        }
        foreach($sonDepartment as $k => $val){
            if($val['level'] == $minDepartmentLevel){
                $res[] = $val;
            }
        }
        return $res;
    }
    
    /**
     * 循环获取部门下所有的部门，获取部门下业绩
     * @param unknown $getMinDepartmentList
     * @return multitype:|multitype:unknown
     */
    public function getDepartmentUserList($getMinDepartmentList){
        if(!is_array($getMinDepartmentList)){
            return array();
        }
        foreach($getMinDepartmentList as $k => $val){
            $userYeji = $this->getUserYeji($val['department_id']);
            $getMinDepartmentList[$k]['rujin'] = $userYeji['zonge'];
            $getMinDepartmentList[$k]['zhebiao'] = $userYeji['nianhuan'];
            $getMinDepartmentList[$k]['yaoqingrencount'] = $userYeji['yaoqingrencount'];
            
        }
        return $getMinDepartmentList;
    }
    
    /**
     * 根据部门id，获取所有用户，然后查询每个用户的业绩，累加，并返回
     * @param number $departmentId
     * @return Ambigous <number, unknown>
     */
    public function getUserYeji($departmentId = 0){
        $ruJinGuiMo  = 0;
        $zheBiaoJinE = 0;
        $keHuCount   = 0;
        $users = $this->bmyjmxDao->getDepartmentUser(intval($departmentId));
        if(is_array($users)){
            foreach ($users as $k => $val){
                $yeji = $this->myResultsService->getSummaryRanking(intval($val['id']));
                $ruJinGuiMo  += $yeji['zonge'];
                $zheBiaoJinE += $yeji['nianhuan'];
                $keHuCount   += $yeji['yaoqingrencount'];
            }
        }
        $department['zonge'] = $ruJinGuiMo;
        $department['nianhuan'] = $zheBiaoJinE;
        $department['yaoqingrencount'] = $keHuCount;
        
        return $department;
    }
    
    /**
     * 二维数组进行排序
     * @param unknown $arrUsers
     */
    public function arraySort($arrUsers){
        
        if(!is_array($arrUsers))return $arrUsers = array();
            
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => 'rujin',       //排序字段
        );
        $arrSort = array();
        foreach($arrUsers AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);
        }
        return $arrUsers;
    }
    
    /**
     * 循环部门，查询部门上级name，重新赋值
     * @param unknown $departmentList
     */
    public function departmentNameAssignment($departmentList){
        if(!is_array($departmentList)) return $departmentList = array();
        foreach($departmentList as $k => $val){
            $this->departmentService->department_name = null;
            $departmentName = $this->departmentService->getDepartmentName(intval($val['department_id']));
            //截取上级部门，拆分开来
            $name = $this->explodeDepartmentName($departmentName);
            $val['department_name'] = $name;
            $this->departmentService->department_name = null;
            $val['rujin'] = number_format($val['rujin'],2,".","");
            $val['zhebiao'] = number_format($val['zhebiao'],2,".","");
            $departmentList[$k]=$val;
        }
        return $departmentList;
    }
    
    /**
     * 截图部门名称
     * @param unknown $departmentName
     */
    public function explodeDepartmentName($departmentName){
        //截取他的上级部门，只取到县级部门
        $explode = explode('->', $departmentName);
        $name = '';
        foreach ($explode as $k1 => $val1){
            if($k1 > 1){
                $name .= ' '.$val1;
            }
        }
        return $name;
    }
}
