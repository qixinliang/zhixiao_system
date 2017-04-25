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

    public function __construct(){
        parent::__construct();
        $this->bmyjmxService 		= InitPHP::getService("bmyjmx");
        $this->adminService 		= InitPHP::getService("admin");
        $this->departmentService 	= InitPHP::getService("department");
        $this->myResultsService 	= InitPHP::getService("myResults");
		$this->authService 			= InitPHP::getService('auth');
        $this->createExcelService 	= InitPHP::getService("createExcel");
        $this->TeamUtilsService 	= InitPHP::getService('TeamUtils');
    }
    
    
    public function run(){
		$this->authService->checkauth('1024');
		
		//分页.
        $pager 	= $this->getLibrary('pager');
        $page 	= $this->controller->get_gp('page')? $this->controller->get_gp('page') : 1;

		//前端搜索参数.
        $sDate 			= $this->controller->get_gp('start_date');
        $eDate 			= $this->controller->get_gp('end_date');
        $departmentId 	= intval($this->controller->get_gp('department_id'));
        $username 		= urldecode($this->controller->get_gp('username'));
        $status 		= $this->controller->get_gp('status');
        
        //登录用户信息
        $user 			= $this->adminService->current_user();
        $userId 		= $this->adminService->GetToZiXiTongUserId(intval($user['id']));
        $did 			= intval($user['department_id']);

        $deparmentList 	= $this->departmentService->getDepartmentList();
        $myDepartment  	= $this->bmyjmxService->getMyDepartment($did);
        
        //根据用户的部门id，获取子部门id
        $sonDepartment = $this->getTree($deparmentList,$did);
        $this->tree = array();
        
        //拼接where条件，和url链接地址
        $arrangeWhereUrl = $this->arrangeWhereUrl('/bmyjmx/run',$departmentId,$username,$sDate,$eDate);

        //获取当前部门下每个用户的明细
        $departmentUserDetail = $this->bmyjmxService->getDepartmentUserDetail(intval($userId),$sonDepartment,$arrangeWhereUrl['where'],$sDate,$eDate);  
        
        //手机号隐藏
        $departmentUserDetail = $this->TeamUtilsService->isShowInfo2($departmentUserDetail);
        
        //离职用户，离职日期大于检索的开始日子，则不现实当前用户的信息
        if(isset($sDate) && !empty($sDate)){
            $departmentUserDetail = $this->dateRetrieve($sDate,$departmentUserDetail);
        }
        
        //分页
        $page = ($page-1)*10 ? ($page-1)*10 : 0;
        $departmentUserDetail_count = count($departmentUserDetail);
        $departmentUserDetail = array_slice($departmentUserDetail, $page,10);
        if($departmentUserDetail_count>0){
            $page_html = $pager->pager($departmentUserDetail_count, 10,$arrangeWhereUrl['url']); //最后一个参数为true则使用默认样式
        }else{
            $page_html=null;
        }
        //条件
        $this->view->assign('start_date', $sDate);
        $this->view->assign('end_date', $eDate);
        $this->view->assign('department_id', $departmentId);
        $this->view->assign('username', $username);
        $this->view->assign('excelUrl',$arrangeWhereUrl['excelUrl']);
        $this->view->assign('status', $status);
        
        //返回数据
        $this->view->assign('my_department', $myDepartment); //返回我的部门信息
        $this->view->assign('my_department_lsit', $sonDepartment); //返回我的子部门列表，用作搜索条件
 		$list2 = $this->departmentService->getDepartmentList2();
		$listJson = json_encode($list2);
		$this->view->assign('list_json',$listJson);

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
        
        /************************************************************
         * @copyright(c): 2017年4月19日
         * @Author:  yuwen
         * @Create Time: 下午9:12:07
         * @qq:32891873
         * @email:fuyuwen88@126.com
         * @增加总额计算，增加新的需求，开会演示增加增加时间2017年4月19日下午9:12:07
         *************************************************************/
        $zonge = 0;//总入金
        $zongnianhua = 0; //总年化
        $zonghuikuan = 0; //总回款
        foreach ($departmentUserDetail as $key=>$val){
            $zonge += $val['zonge'];
            $zongnianhua +=$val['nianhuan'];
            $zonghuikuan += $val['huikuan'];
        }
        $zonge = number_format($zonge,2,".","");
        $zongnianhua = number_format($zongnianhua,2,".","");
        $zonghuikuan = number_format($zonghuikuan,2,".","");
        
        $this->view->assign('zonge', $zonge);
        $this->view->assign('zongnianhua', $zongnianhua);
        $this->view->assign('zonghuikuan', $zonghuikuan);
        
        //左侧样式是否显示高亮样式
        $bmyjmxleftcorpnav = 'yes';
        $this->view->assign('bmyjmxleftcorpnav', $bmyjmxleftcorpnav);
        
        $this->view->display('bmyjmx/run');
    }
    
    /**
     * excel表格导出
     */
    public function createExcel(){
        //创建excel表格
        $sDate = $this->controller->get_gp('start_date'); //获取开始时间
        $eDate = $this->controller->get_gp('end_date'); //获取结束时间
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
        $arrangeWhereUrl = $this->arrangeWhereUrl('bmyjmx/run',$departmentId,$username,$sDate,$eDate);
        
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
        $sDate = $this->controller->get_gp('start_date') ; //获取开始时间
        $eDate = $this->controller->get_gp('end_date'); //获取结束时间
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
        $arrangeWhereUrl = $this->arrangeWhereUrl('/bmyjmx/total',$departmentId,$username,$sDate,$eDate,$city);
        
        //获取用户列表
        $departmentUserDetail = $this->bmyjmxService->getDepartmentUserDetail($userId,$sonDepartment,$arrangeWhereUrl['where'],$sDate,$eDate);
        
        //循环客户列表，获取当前客户的上级部门
        foreach($departmentUserDetail as $k =>$val){
            $deparment_list = $this->departmentService->getDepartmentList();//获取所有部门
            $data = $this->digui($deparment_list,intval($val['department_id']));//递归获取所有部门，组合
            $this->array = array();
            $data = array_reverse($data);
            $departmentUserDetail[$k]['info'] = $data;
        }
        
        //离职用户，离职日期大于检索的开始日子，则不现实当前用户的信息
        if(isset($sDate) && !empty($sDate)){
            $departmentUserDetail = $this->dateRetrieve($sDate,$departmentUserDetail);
        }
        
        //判断是否按照地区筛选
        if(isset($city) && !empty($city)){
           $departmentUserDetail = $this->cityRetrieve($city,$departmentUserDetail);
        }
        
        //分页
        $page = ($page-1)*10 ? ($page-1)*10 : 0;
        $departmentUserDetail_count = count($departmentUserDetail);
        $departmentUserDetail = array_slice($departmentUserDetail, $page,10);
        if($departmentUserDetail_count>0){
            $page_html = $pager->pager($departmentUserDetail_count, 10,$arrangeWhereUrl['url']); //最后一个参数为true则使用默认样式
        }else{
            $page_html=null;
        }
        
        //条件
        $this->view->assign('start_date', $sDate);
        $this->view->assign('end_date', $eDate);

        $this->view->assign('username', $username);
        $this->view->assign('excelUrl',$arrangeWhereUrl['excelUrl']);
        $this->view->assign('status', $status);
        
        /************************************************************
         * @copyright(c): 2017年4月19日
         * @Author:  yuwen
         * @Create Time: 下午9:12:07
         * @qq:32891873
         * @email:fuyuwen88@126.com
         * @增加总额计算，增加新的需求，开会演示增加增加时间2017年4月19日下午9:12:07
         *************************************************************/
        $zonge = 0;//总入金
        $zongnianhua = 0; //总年化
        $zonghuikuan = 0; //总回款
        foreach ($departmentUserDetail as $key=>$val){
            $zonge += $val['zonge'];
            $zongnianhua +=$val['nianhuan'];
            $zonghuikuan += $val['huikuan'];
        }
        $zonge = number_format($zonge,2,".","");
        $zongnianhua = number_format($zongnianhua,2,".","");
        $zonghuikuan = number_format($zonghuikuan,2,".","");
        
        $this->view->assign('zonge', $zonge);
        $this->view->assign('zongnianhua', $zongnianhua);
        $this->view->assign('zonghuikuan', $zonghuikuan);
        
        
        //返回数据
        $this->view->assign('cityDepartment', $cityDepartment);
        $this->view->assign('city',$city);
        $this->view->assign('my_department', $myDepartment); //返回我的部门信息
        $this->view->assign('my_department_lsit', $sonDepartment); //返回我的子部门列表，用作搜索条件
		//部门。。。
 		$list2 = $this->departmentService->getDepartmentList2();
		$listJson = json_encode($list2);
		$this->view->assign('list_json',$listJson);

        $this->view->assign('page_html', $page_html);
        $this->view->assign('user_data', $departmentUserDetail);

        /*
         * @判断当前用户是否有权限访问组织结构cai'dan
         */
        $adminService = InitPHP::getService('admin');
        $userinfo = $adminService->current_user();
        $this->view->assign('gid', $userinfo['gid']);
        $myClients='yes';//默认加样式
        $this->view->assign('myClients', $myClients);
        //左侧样式是否显示高亮样式
        $bmyjmxtotalleftcorpnav = 'yes';
        $this->view->assign('bmyjmxtotalleftcorpnav', $bmyjmxtotalleftcorpnav);
        
        $this->view->display('bmyjtj/run');
    }
    
    /**
     * 拼接检索条件，和url地址
     * @param type $departmentId
     * @param type $username
     * @param type $sDate
     * @param type $eDate
     * @return type
     */
    public function arrangeWhereUrl($url,$departmentId=null,$username=null,$sDate=null,$eDate=null,$city=null){
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
        if(!empty($sDate) && !empty($eDate)){
            $url=$url.'/start_date/'.$sDate.'end_date/'.$eDate;
            $excelUrl=$excelUrl.'/start_date/'.$sDate.'end_date/'.$eDate;
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

        $sDate = $this->controller->get_gp('start_date') ; //获取开始时间
        $eDate = $this->controller->get_gp('end_date'); //获取结束时间
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
        $arrangeWhereUrl = $this->arrangeWhereUrl('/bmyjmx/total',$departmentId,$username,$sDate,$eDate,$city);
        
        //获取用户列表
        $departmentUserDetail = $this->bmyjmxService->getDepartmentUserDetail($userId,$sonDepartment,$arrangeWhereUrl['where'],$sDate,$eDate);
        
        //循环客户列表，获取当前客户的上级部门
        foreach($departmentUserDetail as $k =>$val){
            $deparment_list = $this->departmentService->getDepartmentList();//获取所有部门
            $data = $this->digui($deparment_list,intval($val['department_id']));//递归获取所有部门，组合
            $this->array = array();
            $data = array_reverse($data);
            $departmentUserDetail[$k]['info'] = $data;
        }
        //离职用户，离职日期大于检索的开始日子，则不现实当前用户的信息
        if(isset($sDate) && !empty($sDate)){
            foreach($departmentUserDetail as $k =>$val){
                if($val['status']=='0'){
                    if(strtotime($sDate)>$val['update_time']){
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
	
	/***
	 * 离职用户，离职日期大于检索的开始日子，则不现实当前用户的信息
	 * @param unknown $sDate
	 * @param unknown $departmentUser
	 */
	public function dateRetrieve($sDate,$departmentUser){
	    if(!is_array($departmentUser)) return  $departmentUser = array();
	    foreach($departmentUser as $k =>$val){
	        if($val['status']=='0'){
	            if(strtotime($sDate)>$val['update_time']){
	                unset($departmentUser[$k]);
	            }
	        }
	    }
	    return $departmentUser;
	}
	
	/**
	 * 根据地区进行循环列表
	 * @param unknown $city
	 * @param unknown $departmentUserDetail
	 */
	public function cityRetrieve($city,$departmentUserDetail){
	    if(!is_array($departmentUserDetail)) return  $departmentUserDetail = array();
	    foreach ($departmentUserDetail as $k=>$v){
	        if($v['info'][0] != $city){
	            unset($departmentUserDetail[$k]);
	        }
	    }
	    return $departmentUserDetail;
	}
}
