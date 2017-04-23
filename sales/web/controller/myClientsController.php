<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/**
 * 我的客户控制器
 * @author aaron
 */
class myClientsController extends baseController
{
    public $initphp_list = array('invest','noInvest','detail','createExcel'); //Action白名单
    
    public function __construct()
    {
        parent::__construct();
        $this->adminService = InitPHP::getService("admin");
        $this->myClientsService = InitPHP::getService("myClients");
		$this->authService = InitPHP::getService('auth');
		$this->TeamUtilsService = InitPHP::getService('TeamUtils');
		$this->roleService = InitPHP::getService('role');
    }
    
	//客户业绩default action
	public function run(){
		//权限检查
		$this->authService->checkauth('1020');

		//参数获取
        $uname 		= urldecode($this->controller->get_gp('uname'));
        $phone 		= $this->controller->get_gp('phone');
        $startDate 	= $this->controller->get_gp('start_date');
        $endDate 	= $this->controller->get_gp('end_date');
		$bmyjmx 	= $this->controller->get_gp('bmyjmx');
        $userId 	= intval($this->controller->get_gp('uid'));

        /*
         * 判断当前是否传过来uid，
		 * 如果传入uid，以传入的uid为准，获取客户列表，
         * 否则，自动获取当前登录用户的。
         */
        if(empty($userId)){
            $userInfo 	= $this->adminService->current_user();
            $uid 	  	= $this->adminService->GetToZiXiTongUserId($userInfo['id']);
        }else{
            $uid 		= $userId;
		}
		
		//检索条件
        $searchCond 	= $this->myClientsService->genSearchCond($uname,
			$phone,$startDate,$endDate,$uid);

		//数据计算
		$friendsData 	= $this->myClientsService->getInvestorByUid($uid,$searchCond['where']);
        $friendsList 	= $this->TeamUtilsService->yongJinJiSuan($friendsData['friends']);
        $count 			= $this->myClientsService->clientCount($uid);

		//客户数量总和，是我邀请的客户数量，和分配给我的客户数量相加
        $friendsCount 	= $count['count'] + $friendsData['customer_friends_count']; 


		//分页
        $pager 			= $this->getLibrary('pager');
        $page  			= $this->controller->get_gp('page')?
			$this->controller->get_gp('page') : 1;

        $page 			= ($page-1)*10 ? ($page-1)*10 : 0;
        $friendsCount 	= count($friendsList['friends']);
        $friends 		= array_slice($friendsList['friends'], $page,10);
        $page_html 		= $pager->pager($friendsCount, 10, $arrangeWhereUrl['url']);
        
        //隐藏手机号码
        $friends 		= $this->TeamUtilsService->isShowInfo2($friends);

		//数据映射到前端
        $this->view->assign('uname', $uname);
        $this->view->assign('phone', $phone);
        $this->view->assign('start_date', $startDate);
        $this->view->assign('end_date', $endDate);
       
        $this->view->assign('page_html', $page_html);
        $this->view->assign('username', $userInfo['user']);

        $this->view->assign('nhsyl_count', $friendsList['nhsyl_count']);//年化收益金额
        $this->view->assign('tzje_count', $friendsList['tzje_count']);//投资金额
        $this->view->assign('friendsCount', $friendsCount);//客户统计数量
        $this->view->assign('uid',$userId);
        $this->view->assign('gid', $userInfo['gid']);
        $this->view->assign('friends',$friends);

        $myClients ='yes';//默认加样式
        $this->view->assign('myClients', $myClients);

        //左侧样式是否显示高亮样式
        if(empty($userId)){
            $myClientsleftcorpnav = 'yes';
            $this->view->assign('myClientsleftcorpnav', $myClientsleftcorpnav);
        }else if($bmyjmx == 1){
            //左侧样式是否显示高亮样式
            $bmyjmxleftcorpnav = 'yes';
  			$this->view->assign('bmyjmx', $bmyjmx);
            $this->view->assign('bmyjmxleftcorpnav', $bmyjmxleftcorpnav);
        }

        $this->view->display('myclient/run');
	}
    
    /**
     * 未投资客户
     */
    public function noInvest(){
		//权限检查
		$this->authService->checkauth('1021');
	
		//参数获取
        $userId 	= intval($this->controller->get_gp('uid'));
        $uname 		= urldecode($this->controller->get_gp('uname'));
        $phone 		= $this->controller->get_gp('phone');
        $startDate 	= $this->controller->get_gp('start_date');
        $endDate 	= $this->controller->get_gp('end_date');
 		$bmyjmx 	= $this->controller->get_gp('bmyjmx');

        $pager 		= $this->getLibrary('pager');
        $page  		= $this->controller->get_gp('page') ?
			$this->controller->get_gp('page') : 1;
       
        if(empty($userId)){
            $userInfo 	= $this->adminService->current_user();
            $uid 	  	= $this->adminService->GetToZiXiTongUserId(intval($userInfo['id']));
        }else{
            $uid 		= intval($userId);
        }
        
            
		//检索条件
        $searchCond 	= $this->myClientsService->genSearchCond(
			$uname,$phone,$startDate,$endDate,$uid);

		//计算结果
        $rows 			= $this->myClientsService->getNoInvestCustomers(
			$uid,$searchCond['where']);

		$friends 		= $rows['customers'];
		$friendsCount 	= $rows['count'];
            
       	$page 			= ($page-1)*10 ? ($page-1)*10 : 0;
        $page_html 		= $pager->pager($friendsCount, 10, $searchCond['url']);
            
        //隐藏手机号码
		if(isset($friends) && !empty($friends)){
           	$friends = $this->TeamUtilsService->isShowInfo2($friends);
		}
            
        $this->view->assign('friends',$friends);
        $this->view->assign('page_html', $page_html);
        $this->view->assign('count',$friendsCount);
        $this->view->assign('username', $userInfo['user']);
        $this->view->assign('uname', $uname);
        $this->view->assign('phone', $phone);
        $this->view->assign('start_date', $startDate);
        $this->view->assign('end_date', $endDate);
        $this->view->assign('uid',$uid);
        $this->view->assign('gid', $userinfo['gid']);
        $myClients='yes';//默认加样式
        $this->view->assign('myClients', $myClients);
        if($bmyjmx==1){
            //左侧样式是否显示高亮样式
            $bmyjmxleftcorpnav = 'yes';
            $this->view->assign('bmyjmx', $bmyjmx);
            $this->view->assign('bmyjmxleftcorpnav', $bmyjmxleftcorpnav);
        }else{
            //左侧样式是否显示高亮样式
            $myClientsleftcorpnav = 'yes';
            $this->view->assign('myClientsleftcorpnav', $myClientsleftcorpnav);
        } 
        
        $this->view->display('myclient/noInvest');
    }
    
    /**
     * 客户明细
     */
    public function detail(){
		$this->authService->checkauth('1022');
        
		$bmyjmx 	= $this->controller->get_gp('bmyjmx');
		$clientId 	= intval($this->controller->get_gp('clientId'));
        if(!isset($clientId) || empty($clientId)){
            $this->run();
        }
        
        //根据客户id，获取客户信息
        $clientInfo = $this->myClientsService->getClientInfo($clientId);
        
        //查询当前客户是否投资
        $clientOrder = $this->myClientsService->getFriednOorder($clientId);
        
        //根据客户id查询邀请人id
        $inviterId = $this->myClientsService->getInviter($clientId);
        
        //根据邀请人id，获取邀请人信息
        $info = $this->myClientsService->getInviterDeparture($inviterId['friends']);
		if(!isset($info) || empty($info)){
        	$this->run();
		}
		//根据客户id反查询，friendsid，查询用户信息查找原始邀请人信息
		$originalInviter['inviter_id']   = $info['id'];
		$originalInviter['inviter_name'] = $info['username'];
		$originalInviter['create_time']  = $inviterId['add_date'];
		$originalInviter['investor_cellphone']  = $info['phone'];
		
        if($info['status']=='0'){ //判断邀请人是否离职 1在职 0离职
            
            //查询客户分配后的邀请人信息
            $allocation_inviter = $this->myClientsService->getAllocationInviter($clientId);
            $this->view->assign('allocation_inviter', $allocation_inviter);
            
        }
        //隐藏手机号
        $clientInfo = $this->hiddenPhone($clientInfo);
        
        $this->view->assign('clientOrder',$clientOrder);
        $this->view->assign('clientInfo', $clientInfo);
        $this->view->assign('original_inviter', $originalInviter);
        
        /*
         * @判断当前用户是否有权限访问组织结构cai'dan
         */
        $userInfo = $this->adminService->current_user();
        $this->view->assign('gid', $userInfo['gid']);
        $myClients='yes';//默认加样式
        $this->view->assign('myClients', $myClients);
        
        //左侧样式是否显示高亮样式
        if(empty($bmyjmx)){
            $myClientsleftcorpnav = 'yes';
            $this->view->assign('myClientsleftcorpnav', $myClientsleftcorpnav);
        }else if($bmyjmx==1){
            //左侧样式是否显示高亮样式
            $bmyjmxleftcorpnav = 'yes';
            $this->view->assign('bmyjmxleftcorpnav', $bmyjmxleftcorpnav);
        }
        
        $this->view->display('myclient/detail');
    }

    /**
     * 根据检索条件，拼接where条件，和url链接地址
     * @param string $uname 用户名称
     * @param string $phone 手机号
     * @param string $startDate 开始时间
     * @param string $endDate 结束时间
     * @return  array 拼接的where条件，和url地址
     */
    public function arrangeWhereUrl($uname=null,$phone=null,$startDate=null,$endDate=null,$userId=null){
        $where = ' ';
        //分页地址
        $url = '/myClients/run';
        if(!empty($userId)){
            $url=$url.'/uid/'.$userId;
        }
        if(!empty($uname)){
            $url=$url.'/uname/'.$uname;
            $where.= ' and h.UsrName = "'.$uname.'"';
        }
        if(!empty($phone)){
            $url=$url.'/phone/'.$phone;
            $where.= ' and i.phone = '.$phone;
        }
        if(!empty($startDate)){
            $url=$url.'/start_date/'.$startDate;
            $where.= ' and o.order_time >= '.strtotime($startDate);
        }
        if(!empty($endDate)){
            $url=$url.'/end_date/'.$endDate;
            $where.= ' and o.order_time <= '.strtotime($endDate);
        }
    
        return array('url'=>$url,'where'=>$where);
    }
    /*
     * @隐藏手机号码，新 需求需
     */
    public function hiddenPhone($array){
        $tmparr = array();
        if(!is_array($array)){
            return $tmparr;
        }
        $array['phone']=substr_replace($array['phone'],'****',3,4);
        $tmparr= $array;
        return $tmparr;
    }

	public function createExcel(){
        
        //获取用户检索条件
        $uname = urldecode($this->controller->get_gp('uname'));    //获取用户名
        $phone = $this->controller->get_gp('phone');//手机号
        $startDate = $this->controller->get_gp('start_date');//开始时间
        $endDate = $this->controller->get_gp('end_date');//结束时间
        $userId = intval($this->controller->get_gp('uid')); //获取uid，用户id
        /**
         * 判断当前是否传过来uid，如果传入uid，以传入的uid为准，获取客户列表，否则，自动获取当前登录用户的。
         */
        if(empty($userId)){
            //获取登陆用户信息
            $adminUid=$this->adminService->current_user();
            $uid = $this->adminService->GetToZiXiTongUserId($adminUid['id']);
        }else{
            $uid = intval($userId);//把接受过来的user_id 赋值给uid
        }
        //根据检索条件，拼接where条件，和url链接地址
        $arrangeWhereUrl = $this->arrangeWhereUrl($uname,$phone,$startDate,$endDate,$userId);
        
        //查询所有的投资客户
        $friends = $this->myClientsService->getInvestFriends($uid,$arrangeWhereUrl['where']);
        
        //循环查询出我邀请的客户所属的业务人员
        foreach($friends as $k=>$v){
            $friends[$k]['salesman'] = $this->myClientsService->getSalesmanUsername(intval($v['uid']));
        }
        //查询客户分配记录表里，分配给我的客户id
        $customerRecordList = $this->myClientsService->getCustomerRecordList($uid);
        
        //循环取出客户分配表里面，分配给我的客户信息，并和我的客户数据合并
        $friendsData = $this->myClientsService->mergeData($friends,$customerRecordList,$arrangeWhereUrl['where']);
        
        //循环计算年化收益率和查询当前客户，所属业务人员,统计年化收益金额，统计年化投资金额
        $friendsList = $this->TeamUtilsService->yongJinJiSuan($friendsData['friends']);
        $data = $friendsList['friends'];
		$createExcelService = InitPHP::getService("createExcel"); 

		$createExcelService->run3($data);
	}
}
