<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 团队统计service
 * @author aaron
*/
class teamStatService extends Service
{
    public $tree = array();
    
    public function __construct()
    {
        parent::__construct();
        $this->bmyjmxService = InitPHP::getService("bmyjmx");//获取部门明细
        $this->departmentService = InitPHP::getService("department");
        $this->myResultsService = InitPHP::getService("myResults");
        $this->teamStatDao = InitPHP::getDao("teamStat");
        $this->bmyjmxDao = InitPHP::getDao("bmyjmx");
    }
    
    public function getTeamTop($start_time=null,$end_time=null){
        
        //获取所有的团队经理
        $teamManager = $this->teamStatDao->getTeamManager();
        
        
        foreach($teamManager as $k=>$v){
            $userDepartmentId = intval($v['department_id']);
            
            $res = $this->getDepartmentStat($userDepartmentId, $start_time, $end_time);
            
            $teamManager[$k]['rujin'] = $res['ruJinGuiMo'];
            $teamManager[$k]['zhebiao'] = $res['zheBiaoJinE'];
            $teamManager[$k]['keHuCount'] = $res['keHuCount'];
            $teamManager[$k]['departmentName'] = $res['departmentName'];
        }
        
        krsort($teamManager,'rujin');//根据入金规模排序
        
        return $teamManager;
    }
    
    public function getDepartmentStat($departmentId,$start_time=null,$end_time=null){
        
        $deparmentList = $this->departmentService->getDepartmentList();//获取所有的部门
        
        //获取上级部门名称
        $departmentName = $this->departmentService->getDepartmentName($departmentId,$start_time,$end_time);
        $department = explode('->', $departmentName);
        $departmentName = end($department);
        
        //获取我所有的子部门
        $sonDepartment = $this->getSonDepartment($deparmentList,$departmentId);
        $this->tree = array();
        
        foreach ($sonDepartment as $k1=>$v1){
            $user = $this->bmyjmxDao->getDepartmentUser($v1['department_id']);
            if(!empty($user) && isset($user)){
                $departmentUser[] = $user;
            }
        }
        //根据用户，循环查询所有的业绩明细
        $ruJinGuiMo=0;
        $zheBiaoJinE=0;
        $keHuCount=0;
        foreach($departmentUser as $k1=>$val1){
            foreach ($val1 as $k2=>$val2){
                $userYeji = $this->myResultsService->getSummaryRanking($val2['id'],$start_time,$end_time);
                $ruJinGuiMo  += $userYeji['zonge'];
                $zheBiaoJinE += $userYeji['nianhuan'];
                $keHuCount   += $userYeji['yaoqingrencount'];
            }
        }
        return array('ruJinGuiMo'=>$ruJinGuiMo,'zheBiaoJinE'=>$zheBiaoJinE,'keHuCount'=>$keHuCount,'departmentName'=>$departmentName);
    }
    
    /**
     * 递归循环当前部门下的所有子部门
     * @param unknown $arr
     * @param unknown $pid
     * @param number $step
     * @return Ambigous <multitype:, string>
     */
    public function getSonDepartment($arr,$pid,$step=0){
    
        foreach($arr as $key=>$val) {
            if($val['p_dpt_id'] == $pid) {
                $flg = str_repeat('―',$step);
                $val['step'] = $flg;
                $this->tree[] = $val;
                $this->getSonDepartment($arr , intval($val['department_id']),$step+1);
            }
        }
        return $this->tree;
    }
}