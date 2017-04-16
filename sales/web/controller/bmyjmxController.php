<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/************************************************************
 * @copyright(c): 2017年3月29日
 * @Author:  cxd
 * @Create Time: 下午5:23:16
 * @部门业绩明细
 *************************************************************/
class bmyjmxController extends baseController{
    public $initphp_list = array('createExcel','createExcel2','total'); //Action白名单
    
    public $tree = array();
    public $array = array();
	public $userData = NULL;

    
    public function __construct(){
        parent::__construct();
        $this->bmyjmxService = InitPHP::getService("bmyjmx");
        $this->adminService = InitPHP::getService("admin");//获取管理员信息
        $this->departmentService = InitPHP::getService("department");
        $this->myResultsService = InitPHP::getService("myResults");
		$this->authService = InitPHP::getService('auth');
        $this->createExcelService = InitPHP::getService("createExcel");
    }
    
    
    public function run(){
		$this->authService->checkauth('1024');
		
        $pager= $this->getLibrary('pager'); //分页加载
        
        $page = $this->controller->get_gp('page') ? $this->controller->get_gp('page') : 1 ; //获取当前页码
        $startDate = $this->controller->get_gp('start_date'); //获取开始时间
        $endDate = $this->controller->get_gp('end_date'); //获取结束时间
        $departmentId = intval($this->controller->get_gp('department_id')); //部门id
        $username = urldecode($this->controller->get_gp('username')); //获取姓名
        $status = $this->controller->get_gp('status'); //搜索状态 1代表搜索
        
        //获取登录用户信息
        $user = $this->adminService->current_user();
        $userId = $this->adminService->GetToZiXiTongUserId($user['id']);//获取user表里面的userid
        
        $deparmentList = $this->departmentService->getDepartmentList(); //获取所有的部门列表
        
        $userDepartmentId = intval($user['department_id']);
        
        $myDepartment = $this->bmyjmxService->getMyDepartment($userDepartmentId); //获取我当前用户所在的部门信息
        
        //根据用户的部门id，获取子部门id
        $sonDepartment = $this->getTree($deparmentList,$userDepartmentId);
        $this->tree = array();
//         print_r($sonDepartment);exit;
        //拼接where条件，和url链接地址
        $arrangeWhereUrl = $this->arrangeWhereUrl('/bmyjmx/run',$departmentId,$username,$startDate,$endDate);

        //获取当前部门下每个用户的明细
        $departmentUserDetail = $this->bmyjmxService->getDepartmentUserDetail($userId,$sonDepartment,$arrangeWhereUrl['where'],$startDate,$endDate);  
        
        //离职用户，离职日期大于检索的开始日子，则不现实当前用户的信息
        if(isset($startDate) && !empty($startDate)){
            foreach($departmentUserDetail as $k =>$val){
                if($val['status']=='0'){
                    if(strtotime($startDate)>$val['update_time']){
                        unset($departmentUserDetail[$k]);
                    }
                }
            }
        }
        
        //分页
        $page = ($page-1)*10 ? ($page-1)*10 : 0;
        $departmentUserDetail_count = count($departmentUserDetail);
        $departmentUserDetail = array_slice($departmentUserDetail, $page,10);
        $page_html = $pager->pager($departmentUserDetail_count, 10,$arrangeWhereUrl['url']); //最后一个参数为true则使用默认样式
        
        //条件
        $this->view->assign('start_date', $startDate);
        $this->view->assign('end_date', $endDate);
        $this->view->assign('department_id', $departmentId);
        $this->view->assign('username', $username);
        $this->view->assign('excelUrl',$arrangeWhereUrl['excelUrl']);
        $this->view->assign('status', $status);
        
        //返回数据
        $this->view->assign('my_department', $myDepartment); //返回我的部门信息
        $this->view->assign('my_department_lsit', $sonDepartment); //返回我的子部门列表，用作搜索条件
        $this->view->assign('page_html', $page_html);
        $myClients='yes';//默认加样式
        $this->view->assign('myClients', $myClients);
        $this->view->assign('user_data', $departmentUserDetail);
        
        /*
         * @判断当前用户是否有权限访问组织结构cai'dan
         */
        $adminService = InitPHP::getService('admin');
        $userinfo = $adminService->current_user();
        $this->view->assign('gid', $userinfo['gid']);
        
        $this->view->display('bmyjmx/run');
    }
    
    /**
     * excel表格导出
     */
    public function createExcel(){
        //创建excel表格
        $startDate = $this->controller->get_gp('start_date'); //获取开始时间
        $endDate = $this->controller->get_gp('end_date'); //获取结束时间
        $departmentId = $this->controller->get_gp('department_id'); //部门id
        $username = urldecode($this->controller->get_gp('username')) ; //获取姓名
        
        //获取用户id
        $user = $this->adminService->current_user();
        $userId = $this->adminService->GetToZiXiTongUserId($user['id']);//获取user表里面的userid
        
        $deparmentList = $this->departmentService->getDepartmentList(); //获取所有的部门列表
        $userDepartmentId = intval($user['department_id']);
        //根据用户的部门id，获取子部门id
        $sonDepartment = $this->GetTree($deparmentList,$userDepartmentId);
        
        //拼接where条件，和url链接地址
        $arrangeWhereUrl = $this->arrangeWhereUrl('bmyjmx/run',$departmentId,$username,$startDate,$endDate);
        
        //获取用户列表
        $departmentUserDetail = $this->bmyjmxService->getDepartmentUserDetail($userId,$sonDepartment,$arrangeWhereUrl['where']);

        $this->createExcelService->run($departmentUserDetail);
    }
    
    /**
     * 部门业绩统计
     */
    public function total(){
        $pager= $this->getLibrary('pager'); //分页加载
        
        $page = intval($this->controller->get_gp('page')) ? intval($this->controller->get_gp('page')) : 1 ; //获取当前页码
        $startDate = $this->controller->get_gp('start_date') ; //获取开始时间
        $endDate = $this->controller->get_gp('end_date'); //获取结束时间
        $departmentId = $this->controller->get_gp('department_id'); //部门id
        $username = urldecode($this->controller->get_gp('username')); //获取姓名
        $city = urldecode($this->controller->get_gp('city')); 
        $status = $this->controller->get_gp('status'); //搜索状态 1代表搜索
        
        //获取用户id
        $user = $this->adminService->current_user();
        $userId = $this->adminService->GetToZiXiTongUserId($user['id']);//获取user表里面的userid
        
        $deparmentList = $this->departmentService->getDepartmentList(); //获取所有的部门列表
        $userDepartmentId = intval($user['department_id']);
        $myDepartment = $this->bmyjmxService->getMyDepartment($userDepartmentId); //获取我的部门信息
        
        //获取所有pid为1的部门，当做城市部门
        $cityDepartment = $this->bmyjmxService->getDepartmentList(1);
        
        //根据用户的部门id，获取子部门id
        $sonDepartment = $this->GetTree($deparmentList,$userDepartmentId);
        $this->tree = array();
        
        //拼接where条件，和url链接地址
        $arrangeWhereUrl = $this->arrangeWhereUrl('/bmyjmx/total',$departmentId,$username,$startDate,$endDate,$city);
        
        //获取用户列表
        $departmentUserDetail = $this->bmyjmxService->getDepartmentUserDetail($userId,$sonDepartment,$arrangeWhereUrl['where'],$startDate,$endDate);
        
        //循环客户列表，获取当前客户的上级部门
        foreach($departmentUserDetail as $k =>$val){
            $deparment_list = $this->departmentService->getDepartmentList();//获取所有部门
            $data = $this->digui($deparment_list,intval($val['department_id']));//递归获取所有部门，组合
            $this->array = array();
            $data = array_reverse($data);
            $departmentUserDetail[$k]['info'] = $data;
        }
        //离职用户，离职日期大于检索的开始日子，则不现实当前用户的信息
        if(isset($startDate) && !empty($startDate)){
            foreach($departmentUserDetail as $k =>$val){
                if($val['status']=='0'){
                    if(strtotime($startDate)>$val['update_time']){
                        unset($departmentUserDetail[$k]);
                    }
                }
            }
        }
        
        //判断是否按照地区筛选
        if(isset($city) && !empty($city)){
            foreach ($departmentUserDetail as $k=>$v){
                if($v['info'][0] != $city){
                    unset($departmentUserDetail[$k]);
                }
            }
        }
        
        //分页
        $page = ($page-1)*10 ? ($page-1)*10 : 0;
        $departmentUserDetail_count = count($departmentUserDetail);
        $departmentUserDetail = array_slice($departmentUserDetail, $page,10);
        $page_html = $pager->pager($departmentUserDetail_count, 10,$arrangeWhereUrl['url']); //最后一个参数为true则使用默认样式
        
        //条件
        $this->view->assign('start_date', $startDate);
        $this->view->assign('end_date', $endDate);

        $this->view->assign('username', $username);
        $this->view->assign('excelUrl',$arrangeWhereUrl['excelUrl']);
        $this->view->assign('status', $status);
        
        //返回数据
        $this->view->assign('cityDepartment', $cityDepartment);
        $this->view->assign('city',$city);
        $this->view->assign('my_department', $myDepartment); //返回我的部门信息
        $this->view->assign('my_department_lsit', $sonDepartment); //返回我的子部门列表，用作搜索条件
        $this->view->assign('page_html', $page_html);
        $this->view->assign('user_data', $departmentUserDetail);

		//用于表格导出
		$this->userData = $departmentUserDetail;
        
        /*
         * @判断当前用户是否有权限访问组织结构cai'dan
         */
        $adminService = InitPHP::getService('admin');
        $userinfo = $adminService->current_user();
        $this->view->assign('gid', $userinfo['gid']);
        $myClients='yes';//默认加样式
        $this->view->assign('myClients', $myClients);
        $this->view->display('bmyjtj/run');
    }
    
    /**
     * 拼接检索条件，和url地址
     * @param type $departmentId
     * @param type $username
     * @param type $startDate
     * @param type $endDate
     * @return type
     */
    public function arrangeWhereUrl($url,$departmentId=null,$username=null,$startDate=null,$endDate=null,$city=null){
        $where = '';
        //分页地址
        $excelUrl = '/bmyjmx/createExcel';
        if(!empty($departmentId) && isset($departmentId)){
            $url=$url.'/department_id/'.$departmentId;
            $excelUrl=$excelUrl.'/department_id/'.$departmentId;
            $where.= ' and d.department_id = "'.$departmentId.'"';
        }
        if(!empty($username) && isset($username)){
            $url=$url.'/username/'.$username;
            $excelUrl=$excelUrl.'/username/'.$username;
            $where.= " and z.UsrName = '$username'";
        }
        if(!empty($startDate) && !empty($endDate)){
            $url=$url.'/start_date/'.$startDate.'end_data/'.$endDate;
            $excelUrl=$excelUrl.'/start_date/'.$startDate.'end_data/'.$endDate;
        }
    
        if(!empty($city) && isset($city)){
            $url=$url.'/city/'.$city;
        }
        return array('url'=>$url,'where'=>$where,'excelUrl'=>$excelUrl);
    }
    
    /**
     * 递归循环当前部门下的所有子部门
     * @param unknown $arr
     * @param unknown $pid
     * @param number $step
     * @return Ambigous <multitype:, string>
     */
    public function getTree($arr,$pid,$step=0){
    
        foreach($arr as $key=>$val) {
            if($val['p_dpt_id'] == $pid) {
                $flg = str_repeat('―',$step);
                $val['step'] = $flg;
                $this->tree[] = $val;
                $this->GetTree($arr , intval($val['department_id']),$step+1);
            }
        }
        return $this->tree;
    }
    
    
    /**
     * 获取当前部门所有的上级部门
     * @param unknown $departmentList
     * @param unknown $department_id
     */
    public function digui($departmentList,$department_id){
        foreach($departmentList as $k=>$v){
            if($v['department_id'] == $department_id){
                if($v['p_dpt_id']=='1'){
                    $this->array[] = $v['department_name'];
                }else{
                    $department_id = $v['p_dpt_id'];
                    $this->array[] = $v['department_name'];
                    unset($departmentList[$k]);
                    $this->digui($departmentList, intval($department_id));
                }
            }
        }
        return $this->array;
    }

	//部门业绩统计表格导出
	public function createExcel2(){

        $startDate = $this->controller->get_gp('start_date') ; //获取开始时间
        $endDate = $this->controller->get_gp('end_date'); //获取结束时间
        $departmentId = $this->controller->get_gp('department_id'); //部门id
        $username = urldecode($this->controller->get_gp('username')); //获取姓名
        $city = urldecode($this->controller->get_gp('city')); 
        $status = $this->controller->get_gp('status'); //搜索状态 1代表搜索
        
        //获取用户id
        $user = $this->adminService->current_user();
        $userId = $this->adminService->GetToZiXiTongUserId($user['id']);//获取user表里面的userid
        
        $deparmentList = $this->departmentService->getDepartmentList(); //获取所有的部门列表
        $userDepartmentId = intval($user['department_id']);
        $myDepartment = $this->bmyjmxService->getMyDepartment($userDepartmentId); //获取我的部门信息
        
        //获取所有pid为1的部门，当做城市部门
        $cityDepartment = $this->bmyjmxService->getDepartmentList(1);
        
        //根据用户的部门id，获取子部门id
        $sonDepartment = $this->GetTree($deparmentList,$userDepartmentId);
        $this->tree = array();
        
        //拼接where条件，和url链接地址
        $arrangeWhereUrl = $this->arrangeWhereUrl('/bmyjmx/total',$departmentId,$username,$startDate,$endDate,$city);
        
        //获取用户列表
        $departmentUserDetail = $this->bmyjmxService->getDepartmentUserDetail($userId,$sonDepartment,$arrangeWhereUrl['where'],$startDate,$endDate);
        
        //循环客户列表，获取当前客户的上级部门
        foreach($departmentUserDetail as $k =>$val){
            $deparment_list = $this->departmentService->getDepartmentList();//获取所有部门
            $data = $this->digui($deparment_list,intval($val['department_id']));//递归获取所有部门，组合
            $this->array = array();
            $data = array_reverse($data);
            $departmentUserDetail[$k]['info'] = $data;
        }
        //离职用户，离职日期大于检索的开始日子，则不现实当前用户的信息
        if(isset($startDate) && !empty($startDate)){
            foreach($departmentUserDetail as $k =>$val){
                if($val['status']=='0'){
                    if(strtotime($startDate)>$val['update_time']){
                        unset($departmentUserDetail[$k]);
                    }
                }
            }
        }
        
        //判断是否按照地区筛选
        if(isset($city) && !empty($city)){
            foreach ($departmentUserDetail as $k=>$v){
                if($v['info'][0] != $city){
                    unset($departmentUserDetail[$k]);
                }
            }
        }
			
        $this->createExcelService->run2($departmentUserDetail);
	}
}
