<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/************************************************************
 * @copyright(c): 2017年3月29日
 * @Author:  cxd
 * @Create Time: 下午5:23:16
 * @部门业绩明细
 *************************************************************/
class bmyjmxController extends baseController{
    public $initphp_list = array('createExcel','getcountlist','total'); //Action白名单
    
    public $tree = array();
    
    public function __construct(){
        parent::__construct();
        $this->bmyjmxService = InitPHP::getService("bmyjmx");
        $this->adminService = InitPHP::getService("admin");//获取管理员信息
        $this->departmentService = InitPHP::getService("department");
        $this->myResultsService = InitPHP::getService("myResults");
		$this->authService = InitPHP::getService('auth');
    }
    
    
    public function run(){
		$this->authService->checkauth('1024');
        $pager= $this->getLibrary('pager'); //分页加载
        $page = $this->controller->get_gp('page') ? $this->controller->get_gp('page') : 1 ; //获取当前页码
        $start_date = $this->controller->get_gp('start_date') ? $this->controller->get_gp('start_date') : '' ; //获取开始时间
        $end_date = $this->controller->get_gp('end_date') ? $this->controller->get_gp('end_date') : '' ; //获取结束时间
        $department_id = $this->controller->get_gp('department_id') ? $this->controller->get_gp('department_id') : '' ; //部门id
        $username = urldecode($this->controller->get_gp('username')) ? urldecode($this->controller->get_gp('username')) : '' ; //获取姓名
        
        //获取用户id
        $user = $this->adminService->current_user();
        $deparment_list_all = $this->departmentService->getDepartmentList(); //获取所有的部门列表
        $my_department = $this->bmyjmxService->getMyDepartment($user['department_id']); //获取我的部门信息
        //根据用户的部门id，获取子部门id
        $my_department_lsit = $this->GetTree($deparment_list_all,$user['department_id']);
        $this->tree = array();
        //拼接where条件，和url链接地址
        $arrange_where_url = $this->bmyjmxService->arrange_where_url('bmyjmx/run',$department_id,$username,$start_date,$end_date);
        //获取用户列表
        $user_data = $this->bmyjmxService->getUserDataList($my_department_lsit,$arrange_where_url['where'],$start_date,$end_date);
        
//         foreach($user_data as $k =>$val){
//             $deparment_list = $this->departmentService->getDepartmentList();
//             $data = $this->bmyjmxService->digui($deparment_list,$val['department_id']);
//             $this->bmyjmxService->array=array();
//             $data = array_reverse($data);
//             $user_data[$k]['info'] = $data; 
//         }
        //分页
        $page = ($page-1)*10 ? ($page-1)*10 : 0;
        $user_data_count = count($user_data);
        $user_data = array_slice($user_data, $page,10);
        $page_html = $pager->pager($user_data_count, 10,$arrange_where_url['url']); //最后一个参数为true则使用默认样式
        
        //条件
        $this->view->assign('start_date', $start_date);
        $this->view->assign('end_date', $end_date);
        $this->view->assign('department_id', $department_id);
        $this->view->assign('username', $username);
        $this->view->assign('excelUrl',$arrange_where_url['excelUrl']);
        //返回数据
        $this->view->assign('my_department', $my_department); //返回我的部门信息
        $this->view->assign('my_department_lsit', $my_department_lsit); //返回我的子部门列表，用作搜索条件
        $this->view->assign('page_html', $page_html);
        $this->view->assign('user_data', $user_data);
        $this->view->display('bmyjmx/run');
    }
    
    public function GetTree($arr,$pid,$step=0){
        
        foreach($arr as $key=>$val) {
            if($val['p_dpt_id'] == $pid) {
                $flg = str_repeat('―',$step);
                $val['step'] = $flg;
                $this->tree[] = $val;
                $this->GetTree($arr , $val['department_id'],$step+1);
            }
        }
        return $this->tree;
    }
    
    
    public function createExcel(){
        //创建excel表格
        $start_date = $this->controller->get_gp('start_date') ? $this->controller->get_gp('start_date') : '' ; //获取开始时间
        $end_date = $this->controller->get_gp('end_date') ? $this->controller->get_gp('end_date') : '' ; //获取结束时间
        $department_id = $this->controller->get_gp('department_id') ? $this->controller->get_gp('department_id') : '' ; //部门id
        $username = urldecode($this->controller->get_gp('username')) ? urldecode($this->controller->get_gp('username')) : '' ; //获取姓名
        //获取用户id
        $user = $this->adminService->current_user();
        $deparment_list_all = $this->departmentService->getDepartmentList(); //获取所有的部门列表
        //根据用户的部门id，获取子部门id
        $my_department_lsit = $this->GetTree($deparment_list_all,$user['department_id']);
        //拼接where条件，和url链接地址
        $arrange_where_url = $this->bmyjmxService->arrange_where_url('bmyjmx/run',$department_id,$username,$start_date,$end_date);
        //获取用户列表
        $user_data = $this->bmyjmxService->getUserDataList($my_department_lsit,$arrange_where_url['where']);
        $this->createExcelService = InitPHP::getService("createExcel");
        $this->createExcelService->run($user_data);
    }
    
    //部门业绩统计
    public function total(){
        $pager= $this->getLibrary('pager'); //分页加载
        $page = $this->controller->get_gp('page') ? $this->controller->get_gp('page') : 1 ; //获取当前页码
        $start_date = $this->controller->get_gp('start_date') ? $this->controller->get_gp('start_date') : '' ; //获取开始时间
        $end_date = $this->controller->get_gp('end_date') ? $this->controller->get_gp('end_date') : '' ; //获取结束时间
        $department_id = $this->controller->get_gp('department_id') ? $this->controller->get_gp('department_id') : '' ; //部门id
        $username = urldecode($this->controller->get_gp('username')) ? urldecode($this->controller->get_gp('username')) : '' ; //获取姓名
        $city = urldecode($this->controller->get_gp('city')) ? urldecode($this->controller->get_gp('city')) : '' ; 
        //获取用户id
        $user = $this->adminService->current_user();
        $deparment_list_all = $this->departmentService->getDepartmentList(); //获取所有的部门列表
        $my_department = $this->bmyjmxService->getMyDepartment($user['department_id']); //获取我的部门信息
        //获取所有pid为1的部门，当做城市部门
        $cityDepartment = $this->bmyjmxService->getDepartmentList(1);
        //根据用户的部门id，获取子部门id
        $my_department_lsit = $this->GetTree($deparment_list_all,$user['department_id']);
        $this->tree = array();
        //拼接where条件，和url链接地址
        $arrange_where_url = $this->bmyjmxService->arrange_where_url('bmyjmx/total',$department_id,$username,$start_date,$end_date,$city);
        //获取用户列表
        $user_data = $this->bmyjmxService->getUserDataList($my_department_lsit,$arrange_where_url['where'],$start_date,$end_date);
        //循环客户列表，获取当前客户的上级部门
        foreach($user_data as $k =>$val){
            $deparment_list = $this->departmentService->getDepartmentList();//获取所有部门
            $data = $this->bmyjmxService->digui($deparment_list,$val['department_id']);//递归获取所有部门，并组合
            $this->bmyjmxService->array=array();
            $data = array_reverse($data);
            $user_data[$k]['info'] = $data;
        }
        //判断是否按照地区筛选
        if(isset($city) && !empty($city)){
            foreach ($user_data as $k=>$v){
                if($v[info][0] != $city){
                    unset($user_data[$k]);
                }
            }
        }
        //分页
        $page = ($page-1)*10 ? ($page-1)*10 : 0;
        $user_data_count = count($user_data);
        $user_data = array_slice($user_data, $page,10);
        $page_html = $pager->pager($user_data_count, 10,$arrange_where_url['url']); //最后一个参数为true则使用默认样式
        
        //条件
        $this->view->assign('start_date', $start_date);
        $this->view->assign('end_date', $end_date);
        $this->view->assign('department_id', $department_id);
        $this->view->assign('username', $username);
        $this->view->assign('excelUrl',$arrange_where_url['excelUrl']);
        //返回数据
        $this->view->assign('cityDepartment', $cityDepartment);
        $this->view->assign('city',$city);
        $this->view->assign('my_department', $my_department); //返回我的部门信息
        $this->view->assign('my_department_lsit', $my_department_lsit); //返回我的子部门列表，用作搜索条件
        $this->view->assign('page_html', $page_html);
        $this->view->assign('user_data', $user_data);
        $this->view->display('bmyjtj/run');
    }
    
    public function utf8_array_asort(&$array) {
        if(!isset($array) || !is_array($array)) {
            return false;
        }
        foreach($array as $k=>$v) {
            $array[$k] = iconv('UTF-8', 'GB2312',$v);
        }
        asort($array);
        foreach($array as $k=>$v) {
            $array[$k] = iconv('GB2312', 'UTF-8', $v);
        }
        return true;
    }
    
    
}
