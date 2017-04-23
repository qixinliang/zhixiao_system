<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 主页控制器
 * @author aaron
 */
class indexController extends baseController
{
    public function __construct(){
        parent::__construct();
        $this->adminService    = InitPHP::getService("admin");
        $this->teamStatService = InitPHP::getService("teamStat");
        $this->noticeService   = InitPHP::getService('notice');
    }
	public $initphp_list = array('home');

	/**
	 * 默认action
	 */
	public function run()
	{
	    $userinfo = $this->adminService->current_user();
	     
	    /*
	     * @所属部门
	    */
	    $departmentService = InitPHP::getService("department");
	    $departmentName = $departmentService->getDepartmentName(intval($userinfo['department_id']));
	    $this->view->assign('departmentName', $departmentName);
	    /*
	     * @所属职位
	    */
	    $roleService = InitPHP::getService("role");
	    $position = $roleService->info(intval($userinfo['gid']));
	    $this->view->assign('position', $position['name']);
	    /*
	     * @邀请客户数量
	    */
	    $adminService = InitPHP::getService("admin");
	    $inviteCustomersService = InitPHP::getService("inviteCustomers");
	    $uid = $adminService->GetToZiXiTongUserId(intval($userinfo['id']));
	    $uidList = $inviteCustomersService->getInviteCustomersUidList($uid);
	    $this->view->assign('UidNumber',count($uidList));//邀请的客户数量
	    /*
	     * @本月新增客户数
	    */
	    $monthuidList = $inviteCustomersService->getAccessToCustomerThisMonth($uid);
	    $this->view->assign('montnumber', count($monthuidList));
	    /*
	     * @TOP排行榜
	    */
	    $YearsMonth = date("Y-m");
	    $list = $this->getTopranking($YearsMonth);
	    $list = $this->SortAnArray($list);
	    $output = array_slice($list, 0,5);//显示前5调数据
	    
	    /**
	     * 团队精英TOP排行榜
	     */
	    $TeamTopList = $this->teamStatService->getTeamTop();
	    $isGolden = $this->checkTheGolden($TeamTopList);
	    //检查入金是否有金额
	    $this->view->assign('isGolden', $isGolden);
	    
	    $this->view->assign('TeamTopList', $TeamTopList);
	    
	    /**
	     * 部门管理统计
	     */
	    $userId = intval($userinfo['id']);
	    $departmentId = intval($userinfo['department_id']);
	    //本月新增客户数量
	    $curClientCount = $this->teamStatService->getDepartmentStat($departmentId,null,null,$userId);
	    $this->view->assign('curClientCount', $curClientCount['keHuCount']);
	    
	    //累计客户数量
	    $start_time = date('Y-m-d H:i:s',$userinfo['regtime']);//开始时间
	    $end_time = date('Y-m-d H:i:s',time());//结束时间
	    $customersCount = $this->teamStatService->getDepartmentStat($departmentId,$start_time,$end_time,$userId);
	    $this->view->assign('customersCount', $customersCount['keHuCount']);
	    
	    //上月入金规模，折标规模
	    $datey = $this->getlastMonthDays(date("Y-m-d H:i:s"));
	    $shangYueStat = $this->teamStatService->getDepartmentStat($departmentId,$datey['start'],$datey['end'],$userId);
	    $this->view->assign('shangYueStat', $shangYueStat);
        
	    
	    //最新公告
	    $notice = $this->noticeService->getLatestNotice();
	    $this->view->assign('notice', $notice);
        
	    //检查是否有数据
	    $checkAmount = $this->checkTheAmountOf($output);
	    $this->view->assign('checkAmount', $checkAmount);
	    $this->view->assign('output', $output);
	    $this->view->assign('YearsMonth', $YearsMonth);
	    /*
	     * @个人管理 上月入金规模
	    */
	    $datey = $this->getlastMonthDays(date("Y-m-d H:i:s"));
	    $myResultsService = InitPHP::getService("myResults");
	    $getlastMonthlist = $myResultsService->getTopranking(intval($uid),$datey['start'],$datey['end']);
	    $this->view->assign('getlastMonthzonge', $getlastMonthlist['zonge']);
	    $this->view->assign('getlastMonthnianhuan', $getlastMonthlist['nianhuan']);
	    $this->view->assign('gid', $userinfo['gid']);
	    $this->view->assign('list', $userinfo);
	    
	    $index='yes';//默认加样式
	    $this->view->assign('index', $index);
	    
	    $this->view->assign('title', "百合贷直销系统-首页");
	    $this->view->display("index/run");
	}

    /**
     * 欢迎页
     */
    public function home()
    {
        $userinfo = $this->adminService->current_user();
        /*
         * @所属部门
         */
        $departmentService = InitPHP::getService("department");
        $departmentName = $departmentService->getDepartmentName(intval($userinfo['department_id']));
        $this->view->assign('departmentName', $departmentName);
        /*
         * @所属职位
         */
        $roleService = InitPHP::getService("role");
        $position = $roleService->info(intval($userinfo['gid']));
        $this->view->assign('position', $position['name']);
        /*
         * @邀请客户数量
         */
        $adminService = InitPHP::getService("admin");
        $inviteCustomersService = InitPHP::getService("inviteCustomers");
        $uid = $adminService->GetToZiXiTongUserId(intval($userinfo['id']));
        $uidList = $inviteCustomersService->getInviteCustomersUidList($uid);
        $this->view->assign('UidNumber',count($uidList));//邀请的客户数量
        /*
         * @本月新增客户数
         */
        $monthuidList = $inviteCustomersService->getAccessToCustomerThisMonth($uid);
        $this->view->assign('montnumber', count($monthuidList));
        /*
         * @TOP排行榜
         */
        $YearsMonth = date("Y-m");
        $list = $this->getTopranking($YearsMonth);
        $list = $this->SortAnArray($list);
        $output = array_slice($list, 0,5);//显示前5调数据
        //检查是否有数据
        $checkAmount = $this->checkTheAmountOf($output);
        $this->view->assign('checkAmount', $checkAmount);
        $this->view->assign('output', $output);
        $this->view->assign('YearsMonth', $YearsMonth);
        /*
         * @个人管理 上月入金规模
         */
        $datey = $this->getlastMonthDays(date("Y-m-d H:i:s"));
        $myResultsService = InitPHP::getService("myResults");
        $getlastMonthlist = $myResultsService->getTopranking($uid,$datey['start'],$datey['end']);
        
        $this->view->assign('getlastMonthzonge', $getlastMonthlist['zonge']);
        $this->view->assign('getlastMonthnianhuan', $getlastMonthlist['nianhuan']);
        $this->view->assign('list', $userinfo);
        $index='yes';//默认加样式
        $this->view->assign('index', $index);
        $this->view->display("index/home");
    }
    /************************************************************
     * @copyright(c): 2017年3月29日
     * @Author:  yuwen
     * @Create Time: 下午4:45:11
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @首页排行榜
     *************************************************************/
    public function getTopranking($YearsMonth){
        $adminService = InitPHP::getService("admin");
        $myResultsService = InitPHP::getService("myResults");
        $userlist = $adminService->admin_list();
        foreach ($userlist as $key=>$val){
            $val['yaoqingrencount']=0.00;
            $val['zonge']=0;
            $val['nianhuan']=0;
            $val['huikuan']=0;
            $uid = $adminService->GetToZiXiTongUserId($val['id']);//获取当前登录的用户uid
            if(intval($uid)>0){
                $res = $myResultsService->getTopranking($uid,$YearsMonth);
                $val['yaoqingrencount']=$res['yaoqingrencount'];
                $val['zonge']=$res['zonge'];
                $val['nianhuan']=$res['nianhuan'];
                $val['huikuan']=$res['huikuan'];
            }
            $userlist[$key]=$val;
        }
       return $userlist;
    }
    /************************************************************
     * @copyright(c): 2017年3月29日
     * @Author:  yuwen
     * @Create Time: 下午5:06:39
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @数组排序
     * $sort = array(
     * 'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     * 'field'=> 'zonge',       //排序字段
     * );
     *************************************************************/
    public function SortAnArray($array){
        $arrSort = array();
        if(empty($array)){
            return $arrSort;
        }
        
        foreach($array AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => 'zonge',       //排序字段
        );
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $array);
        }
        return $array;
    }
    /************************************************************
     * @copyright(c): 2017年3月29日
     * @Author:  yuwen
     * @Create Time: 下午8:08:02
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @获取上个月的时间
     *************************************************************/
   public function getlastMonthDays($date){
        $val=array();
        $timestamp=strtotime($date);
        $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
        $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
        $val['start']=$firstday;
        $val['end']=$lastday;
        return $val;
    }
    /************************************************************
     * @copyright(c): 2017年3月31日
     * @Author:  yuwen
     * @Create Time: 上午11:35:50
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @检查是否有数据交易金额
     *************************************************************/
    public function checkTheAmountOf($array){
        $amount=0;
        if(empty($array)){
            return false;
        }
        foreach ($array as $key=>$val){
            $amount+=$val['zonge'];
        }
        if($amount>0){
            return true;
        }else{
            return false;
        }
    }
    /************************************************************
     * @copyright(c): 2017年4月20日
     * @Author:  yuwen
     * @Create Time: 下午2:27:18
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @检查首页团队排行榜是否有数据
     *************************************************************/
    public function checkTheGolden($array){
        $amount=0;
        if(empty($array)){
            return false;
        }
        foreach ($array as $key=>$val){
            $amount+=$val['rujin'];
        }
        if($amount>0){
            return true;
        }else{
            return false;
        }
    }
}
