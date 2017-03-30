<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/************************************************************
 * @copyright(c): 2017年3月29日
 * @Author:  cxd
 * @Create Time: 下午5:23:16
 * @部门业绩明细
 *************************************************************/
class bmyjmxController extends baseController{
    public $initphp_list = array('createExcel'); //Action白名单
    
    public function __construct(){
        parent::__construct();
        $this->bmyjmxService = InitPHP::getService("bmyjmx");
        $this->adminService = InitPHP::getService("admin");//获取管理员信息
        $this->departmentService = InitPHP::getService("department");
        $this->myResultsService = InitPHP::getService("myResults");
    }
    
    
    public function run(){
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
        //拼接where条件，和url链接地址
        $arrange_where_url = $this->bmyjmxService->arrange_where_url($department_id,$username,$start_date,$end_date);
        //获取用户列表
        $user_data = $this->bmyjmxService->getUserDataList($my_department_lsit,$arrange_where_url['where']);
        
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
    
    private function GetTree($arr,$pid,$step=0){
        global $tree;
        foreach($arr as $key=>$val) {
            if($val['p_dpt_id'] == $pid) {
                $flg = str_repeat('―',$step);
                $val['step'] = $flg;
                $tree[] = $val;
                $this->GetTree($arr , $val['department_id'],$step+1);
            }
        }
        return $tree;
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
        $arrange_where_url = $this->bmyjmxService->arrange_where_url($department_id,$username,$start_date,$end_date);
        //获取用户列表
        $user_data = $this->bmyjmxService->getUserDataList($my_department_lsit,$arrange_where_url);
        $this->createExcelService = InitPHP::getService("createExcel");
        $this->createExcelService->run($user_data);
    }
}