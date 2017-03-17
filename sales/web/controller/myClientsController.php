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
        $uname = $this->controller->get_gp('uname');    //获取用户名
        $phone = $this->controller->get_gp('phone');
        $start_date = $this->controller->get_gp('start_date');
        $end_date = $this->controller->get_gp('end_date');
        //条件判断
        $where = ' ';
        if($uname){
            $where.= ' and h.UsrName = "'.$uname.'"';
            $this->view->assign('uname', $uname);
        }
        if($phone){
            $where.= ' and h.UsrMp = '.$phone;
            $this->view->assign('phone', $phone);
        }
        if($start_date){
            $where.= ' and d.start_date >= '.strtotime($start_date);
            $this->view->assign('start_date', $start_date);
        }
        if($end_date){
            $where.= ' and d.end_date <= '.strtotime($end_date);
            $this->view->assign('end_date', $end_date);
        }
        //获取登陆用户信息
        $user=$this->adminService->current_user();
        $userid = $this->myClientsService->getUserId($user['user']); //根据登陆，获取用户id
        
        $limit = ($page-1)*10 ? ($page-1)*10 : 0;
        $friends = $this->myClientsService->getFriends($userid['id'],$limit,$where);
        $friendsCount = $this->myClientsService->getFriendsCount($userid['id'],$where);//统计条数
        $url = 'myClients/run';
        if($phone!=''){
            $url=$url.'/phone/'.$phone;
        }
        if($uname!=''){
            $url=$url.'/uname/'.$uname;
        }
        if($start_date!=''){
            $url=$url.'/start_date/'.$start_date;
        }
        if($end_date!=''){
            $url=$url.'/end_date/'.$end_date;
        }
        $page_html = $pager->pager($friendsCount['count'], 10, $url); //最后一个参数为true则使用默认样式
        $friendsList = $this->TeamUtilsService->yongJinJiSuan($friends);//计算每笔订单的佣金

        $this->view->assign('page',$page);
        $this->view->assign('page_html', $page_html);
        $this->view->assign('username', $userid['username']);
        $this->view->assign('friends',$friendsList);
        $this->view->display('myClient/run');
    }
}