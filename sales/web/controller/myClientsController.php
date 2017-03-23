<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/**
 * 我的客户控制器
 * @author aaron
 */
class myClientsController extends baseController
{
    public $initphp_list = array(''); //Action白名单
    
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
        $this->TeamUtilsService = InitPHP::getService("TeamUtils");
        $pager= $this->getLibrary('pager'); //分页加载
        $page = $this->controller->get_gp('page') ? $this->controller->get_gp('page') : 1 ; //获取当前页码
        
        //获取用户检索条件
        $uname = urldecode($this->controller->get_gp('uname')) ? urldecode($this->controller->get_gp('uname')) : '';    //获取用户名
        $phone = $this->controller->get_gp('phone') ? $this->controller->get_gp('phone') : '';
        $start_date = $this->controller->get_gp('start_date') ? $this->controller->get_gp('start_date') : '';
        $end_date = $this->controller->get_gp('end_date') ? $this->controller->get_gp('end_date') : '';
        $status = $this->controller->get_gp('status') ? $this->controller->get_gp('status') : 0; //null 投资用户 ，1未投资用户列表
       
        //获取登陆用户信息
        $user=$this->adminService->current_user();
        $userid = $this->myClientsService->getUserId($user['user']); //根据登陆，获取用户id
        if(count($userid)==0){
            exit("未获取用户信息！");
        }
        
        //获取用户邀请的好友id列表（投资，和未投资）
        $friendIds = $this->myClientsService->getfriendsIdList($userid['id'],$status);
        if($friendIds==''){
            exit("还没有未投资用户！");
        }
        //根据检索条件，拼接where条件，和url链接地址
        $arrange_where_url = $this->myClientsService->arrange_where_url($uname,$phone,$start_date,$end_date);

        $page = ($page-1)*10 ? ($page-1)*10 : 0;
        if($status!='1'){//1查询未投资用户信息 0查询投资用户信息
            $friends = $this->myClientsService->getInvestFriends($friendIds,$page,10,$arrange_where_url['where']);//查询所有数据
            $friendsCount = $this->myClientsService->getInvestFriendsCount($friendIds,$arrange_where_url['where']);//统计条数
        }else{
            $friends = $this->myClientsService->getNoInvestFriends($friendIds,$page,10,$arrange_where_url['where']);//查询所有数据
            $friendsCount = $this->myClientsService->getNoInvestFriendsCount($friendIds,$arrange_where_url['where']);//统计条数
        }
        $page_html = $pager->pager($friendsCount['count'], 10, $arrange_where_url['url']); //最后一个参数为true则使用默认样式
        $friendsList = $this->TeamUtilsService->yongJinJiSuan($friends);//计算每笔订单的佣金

        //映射条件数据到前台页面
        $this->view->assign('uname', $uname);
        $this->view->assign('phone', $phone);
        $this->view->assign('start_date', $start_date);
        $this->view->assign('end_date', $end_date);
        $this->view->assign('status',$status);
        $this->view->assign('count',$friendsCount['count']);//投资和未投资统计人数
        //分页
        $this->view->assign('page',$page);
        $this->view->assign('page_html', $page_html);
        $this->view->assign('username', $userid['username']);
        //数据列表
        $this->view->assign('friends',$friendsList);
        $this->view->display('myClient/run');
    }
}
