<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/************************************************************
 * @copyright(c): 2017年2月27日
 * @Author:  yuwen
 * @Create Time: 下午5:23:16
 * @qq:32891873
 * @email:fuyuwen88@126.com
 * @我的业绩
 *************************************************************/
class myResultsController extends baseController{
    public $initphp_list = array(); //Action白名单

    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午6:05:29
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @构造函数自动加载
     *************************************************************/
    public function __construct(){
        parent::__construct();
    }
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午6:05:46
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @我的业绩默认列表
     *************************************************************/
    public function run(){
        $adminService = InitPHP::getService("admin");//获取管理员信息
        $myResultsService = InitPHP::getService("myResults");
        $userinfo = $adminService->current_user();
        $res = $myResultsService->ResultsList($userinfo);
        $renshucount=0;
        if(!empty($res['data'])){
           foreach ($res['data'] as $Key=>$val){
              $renshucount+= $val['yaoqingrencount'];
           } 
        }
        $this->view->assign('renshucount',$renshucount);
        $this->view->assign('list',$res['data']);
        $this->view->display("myresults/run"); //使用模板
    }
   
}
