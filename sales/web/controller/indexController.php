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
        $this->adminService    		= InitPHP::getService("admin");
        $this->teamStatService 		= InitPHP::getService("teamStat");
        $this->noticeService   		= InitPHP::getService('notice');
        $this->departmentService   	= InitPHP::getService('department');
		$this->roleService 			= InitPHP::getService('role');
	    $this->icService 			= InitPHP::getService("inviteCustomers");
    }
	public $initphp_list = array('run');

	public function run(){
		//用户信息
		$userInfo = $this->adminService->current_user();
		if(!isset($userInfo) || empty($userInfo)){
			exit(json_encode(array('status' => -1,'message' => 'invalid user!')));
		}
	
		//部门.
		$dptName = $this->departmentService->getDepartmentName(intval($userInfo['department_id']));
	    $this->view->assign('departmentName', $dptName);
	
		//职位.
		$row 	= $this->roleService->info(intval($userInfo['gid']));
		$this->view->assign('position',$row['name']);

		//邀请客户数量
		$uid 	= $this->adminService->GetToZiXiTongUserId(intval($userInfo['id']));
		$rows   = $this->icService->getInviteCustomersUidList($uid);
		$cnt    = 0;
		if(isset($rows) && is_array($rows)){
			$cnt = count($rows);
		}
	    $this->view->assign('UidNumber',$cnt);

		//本月新增客户数
	    $rows 	= $this->icService->getAccessToCustomerThisMonth($uid);
		$cnt    = 0;
		if(isset($rows) && is_array($rows)){
			$cnt = count($rows);
		}
	    $this->view->assign('montnumber', $cnt);
	
		//个人业绩排行榜
	    $curYm 			= date("Y-m");
	    $list  			= $this->getTopranking($curYm);
	    $list  			= $this->SortAnArray($list);
	    $output 		= array_slice($list, 0,5);
	    $check 			= $this->check($output,1);
	    $this->view->assign('checkAmount', $check);
	    $this->view->assign('output', $output);
	    $this->view->assign('YearsMonth', $curYm);

	    //个人上月入金规模
	    $lm = $this->getlastMonthDays(date("Y-m-d H:i:s"));
	    $myService = InitPHP::getService("myResults");
	    $getlastMonthlist = $myService->getTopranking(intval($uid),$lm['start'],$lm['end']);
	    $this->view->assign('getlastMonthzonge', $getlastMonthlist['zonge']);
	    $this->view->assign('getlastMonthnianhuan', $getlastMonthlist['nianhuan']);
	    $this->view->assign('gid', $userInfo['gid']);
	    $this->view->assign('list', $userInfo);


		//团队排行榜
	    $teamList = $this->teamStatService->getTeamTop();
	    $isGolden = $this->check($teamList,0);
	    $this->view->assign('isGolden', $isGolden);
	    $this->view->assign('team_list', $teamList);

		//部门相关数据...
	    $userId 		= intval($userInfo['id']);
	    $departmentId 	= intval($userInfo['department_id']);

		//1. 新增客户数
	    $rows = $this->teamStatService->getDepartmentStat($departmentId,null,null,$userId);
		$cnt  = 0;
		if(isset($rows)){
			$cnt = $rows['keHuCount'];
		}
	    $this->view->assign('curClientCount', $cnt);

		//2. 累计客户数
	    $sTime = date('Y-m-d H:i:s',$userInfo['regtime']);
	    $eTime = date('Y-m-d H:i:s',time());
	    $customersCount = $this->teamStatService->getDepartmentStat($departmentId,$sTime,$eTime,$userId);
	    $this->view->assign('customersCount', $customersCount['keHuCount']);
		
		//3.上月入金、折标
	    $lmStat = $this->teamStatService->getDepartmentStat($departmentId,$lm['start'],$lm['end'],$userId);
	    $this->view->assign('shangYueStat', $lmStat);
	
		//最新公告
	    $notice = $this->noticeService->getLatestNotice();
	    $this->view->assign('notice', $notice);

	    $index='yes';//默认加样式
	    $this->view->assign('index', $index);
	    $this->view->assign('title', "百合贷直销系统-首页");
	    $this->view->display("index/run");
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

	//获取上个月的时间 by yuwen
	public function getlastMonthDays($date){
        $val=array();
        $timestamp=strtotime($date);
        $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
        $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
        $val['start']=$firstday;
        $val['end']=$lastday;
        return $val;
    }

	//检测是否有入金及总额的数据
	public function check($arr,$type){
		if(!isset($arr) || empty($arr)){
			return false;
		}
		$amount = 0;
		switch($type){
			case 0:
				foreach($arr as $v){
					$amount += $v['rujin'];
				}
				if($amount > 0) {
					return true;
				}
				break;
			case 1:
				foreach($arr as $v){
					$amount += $v['zonge'];
				}
				if($amount > 0) {
					return true;
				}
				break;
			default:
				break;
		}
		return false;
	}
}
