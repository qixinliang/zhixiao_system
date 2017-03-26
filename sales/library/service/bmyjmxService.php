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
    
    public function getUserDataList($my_department_lsit,$where=''){
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
                $userYeji = $this->myResultsService->getTopranking($val1['id'],'2016-10',$end_date);
                //$userYeji = $this->myResultsService->getTopranking($val1['id'],$start_date,$end_date);
                $val1['yaoqingrencount'] = $userYeji['yaoqingrencount'];
                $val1['zonge'] = $userYeji['zonge'];
                $val1['nianhuan'] = $userYeji['nianhuan'];
                $val1['huikuan'] = $userYeji['huikuan'];
                $user_data[] = $val1;
            }
        }
        return $user_data;
    }
}
