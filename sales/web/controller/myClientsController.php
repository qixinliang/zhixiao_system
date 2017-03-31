<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/**
 * 我的客户控制器
 * @author aaron
 */
class myClientsController extends baseController
{
    public $initphp_list = array('invest','noInvest','detail'); //Action白名单
    
    public function __construct()
    {
        parent::__construct();
        $this->adminService = InitPHP::getService("admin");//获取管理员信息
        $this->myClientsService = InitPHP::getService("myClients");
    }
    
    /**
     * 默认Action
     * @author aaron
     */
    public function run(){
        $pager= $this->getLibrary('pager'); //分页加载
        $page = $this->controller->get_gp('page') ? $this->controller->get_gp('page') : 1 ; //获取当前页码
        $this->teamUtilsService = InitPHP::getService('TeamUtils');
        
        //获取用户检索条件
        $uname = urldecode($this->controller->get_gp('uname')) ? urldecode($this->controller->get_gp('uname')) : '';    //获取用户名
        $phone = $this->controller->get_gp('phone') ? $this->controller->get_gp('phone') : '';//手机号
        $start_date = $this->controller->get_gp('start_date') ? $this->controller->get_gp('start_date') : '';//开始时间
        $end_date = $this->controller->get_gp('end_date') ? $this->controller->get_gp('end_date') : '';//结束时间
        $user_id = intval($this->controller->get_gp('uid')) ? intval($this->controller->get_gp('uid')) : ''; //获取uid，用户id
        /**
         * 判断当前是否传过来uid，如果传入uid，以传入的uid为准，获取客户列表，否则，自动获取当前登录用户的。
         */
        if(empty($user_id)){
            //获取登陆用户信息
            $adminUid=$this->adminService->current_user();
            $uid = $this->adminService->GetToZiXiTongUserId($adminUid['id']);
        }else{
            $uid = $this->adminService->GetToZiXiTongUserId($user_id);
        }
        
        if(empty($uid)){
            exit("未获取用户信息！");
        }
        //根据检索条件，拼接where条件，和url链接地址
        $arrange_where_url = $this->myClientsService->arrange_where_url($uname,$phone,$start_date,$end_date,$user_id);
        //查询所有的我的客户
        $friends = $this->myClientsService->getInvestFriends($uid,$arrange_where_url['where']);//查询所有数据
        //循环查询出我邀请的客户所属的业务人员
        foreach($friends as $k=>$v){
            $friends[$k]['salesman'] = $this->myClientsService->getSalesmanUsername($v['uid']);
        }
        //查询客户分配记录表里，分配给我的客户id
        $customer_record_list = $this->myClientsService->getCustomerRecordList($uid);
        
        //循环取出客户分配表里面，分配给我的客户信息，并和我的客户数据合并
        $friends_data = $this->myClientsService->mergeData($friends,$customer_record_list,$arrange_where_url);
        
        //循环计算年化收益率和查询当前客户，所属业务人员,统计年化收益金额，统计年化投资金额
        $friends_list = $this->teamUtilsService->yongJinJiSuan($friends_data['friends']);
        //分页
        $page = ($page-1)*10 ? ($page-1)*10 : 0;
        $friendsCount = count($friends_list['friends']);
        $friends = array_slice($friends_list['friends'], $page,10);
        $page_html = $pager->pager($friendsCount, 10, $arrange_where_url['url']); //最后一个参数为true则使用默认样式

        //统计用户的客户数量
        $count = $this->myClientsService->clientCount($uid);
        $friendsCount = $count['count'] + $friends_data['customer_friends_count']; //客户数量总和，是我邀请的客户数量，和分配给我的客户数量相加
        //映射条件数据到前台页面
        $this->view->assign('uname', $uname);
        $this->view->assign('phone', $phone);
        $this->view->assign('start_date', $start_date);
        $this->view->assign('end_date', $end_date);
        //分页
        $this->view->assign('page_html', $page_html);
        $this->view->assign('username', $adminUid['user']);
        //统计数据
        $this->view->assign('nhsyl_count', $friends_list['nhsyl_count']);//年化收益金额
        $this->view->assign('tzje_count', $friends_list['tzje_count']);//投资金额
        $this->view->assign('friendsCount', $friendsCount);//客户统计数量
        $this->view->assign('uid',$user_id);
        //数据列表
        $this->view->assign('friends',$friends);
        $this->view->display('myclient/run');
    }
    
    /**
     * 未投资客户
     */
    public function noInvest(){
        $pager= $this->getLibrary('pager'); //分页加载
        $page = $this->controller->get_gp('page') ? $this->controller->get_gp('page') : 1 ; //获取当前页码
        $user_id = intval($this->controller->get_gp('uid')) ? intval($this->controller->get_gp('uid')) : ''; //获取uid，用户id
        
        //获取用户检索条件
        $uname = urldecode($this->controller->get_gp('uname')) ? urldecode($this->controller->get_gp('uname')) : '';    //获取用户名
        $phone = $this->controller->get_gp('phone') ? $this->controller->get_gp('phone') : '';
        $start_date = $this->controller->get_gp('start_date') ? $this->controller->get_gp('start_date') : '';
        $end_date = $this->controller->get_gp('end_date') ? $this->controller->get_gp('end_date') : '';
       
        //获取登陆用户信息
        if(empty($user_id)){
            //获取登陆用户信息
            $adminUid=$this->adminService->current_user();
            $uid = $this->adminService->GetToZiXiTongUserId($adminUid['id']);
        }else{
            $uid = $this->adminService->GetToZiXiTongUserId($user_id);
        }
        if(empty($uid)){
            exit("未获取用户信息！");
        }
        
        //获取分配记录表的客户信息
        $allocation = $this->myClientsService->getCustomerRecordList($uid);
        //获取用户邀请的好友id列表（投资，和未投资）
        $friendIds = $this->myClientsService->getfriendsIdList($uid,$allocation);
        if($friendIds!=''){
            //根据检索条件，拼接where条件，和url链接地址
            $arrange_where_url = $this->myClientsService->arrange_where_url($uname,$phone,$start_date,$end_date,$user_id);
            $page = ($page-1)*10 ? ($page-1)*10 : 0;
            $friends = $this->myClientsService->getNoInvestFriends($friendIds,$arrange_where_url['where']);//查询所有数据
            $friendsCount = $this->myClientsService->getNoInvestFriendsCount($friendIds,$arrange_where_url['where']);//统计条数
            $page_html = $pager->pager($friendsCount['count'], 10, $arrange_where_url['url']); //最后一个参数为true则使用默认样式
            //分页
            $this->view->assign('page_html', $page_html);
            //数据列表
            $this->view->assign('friends',$friends);
            $this->view->assign('count',$friendsCount['count']);//投资和未投资统计人数
            $this->view->assign('username', $adminUid['user']);
        }
        //条件数据
        $this->view->assign('uname', $uname);
        $this->view->assign('phone', $phone);
        $this->view->assign('start_date', $start_date);
        $this->view->assign('end_date', $end_date);
        $this->view->assign('uid',$user_id);
        
        $this->view->display('myclient/noInvest');
    }
    
    /**
     * 客户明细
     */
    public function detail(){
        //获取用户当前客户id
        $clientId = intval($this->controller->get_gp('clientId')) ? intval($this->controller->get_gp('clientId')) : '' ; //获取当前页码
        if(!isset($clientId)){
            $this->view->display('myClient/run');
        }
        //根据客户id，获取客户信息
        $clientInfo = $this->myClientsService->getClientInfo($clientId);
        //查询当前客户是否投资
        $clientOorder = $this->myClientsService->getFriednOorder($clientId);
        //根据客户id关联邀码，cp_user_yaoqingma_list，查询用户uid，判断用是否离职
        $info = $this->myClientsService->getInviterDeparture($clientId);
        if($info['departure']=='1'){ //判断邀请人是否离职 1在职 0离职
            //根据客户id反查询，friendsid，查询用户信息查找原始邀请人信息
            $original_inviter['inviter_id']   = $info['id'];
            $original_inviter['inviter_name'] = $info['add_date'];
            $original_inviter['create_time']  = $info['add_date'];
        }else{ //如果离职
            //查询zx_customer_pool，zx_customer_record 查询原始邀请人，和分配邀请人
            $original_inviter = $this->myClientsService->getoOriginalInviter($clientId);
            //查询客户分配后的邀请人信息
            $allocation_inviter = $this->myClientsService->getAllocationInviter($clientId);
            $this->view->assign('allocation_inviter', $allocation_inviter);
        }
        $this->view->assign('clientOorder',$clientOorder);
        $this->view->assign('clientInfo', $clientInfo);
        $this->view->assign('original_inviter', $original_inviter);
        $this->view->display('myclient/detail');
    }
}
