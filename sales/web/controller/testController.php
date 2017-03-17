<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/************************************************************
 * @copyright(c): 2017年2月27日
 * @Author:  yuwen
 * @Create Time: 下午5:23:16
 * @qq:32891873
 * @email:fuyuwen88@126.com
 * @测试
 *************************************************************/
class testController extends baseController{
    public $initphp_list = array('add','AddSave','edit','EditSave','Del'); //Action白名单

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
     * @显示默认列表页面
     *************************************************************/
    public function run(){
       $adminService = InitPHP::getService('admin');
       $customerDao = InitPHP::getDao('customer');
       $userinfo = $adminService->current_user();
       
       $res = $customerDao->add($userinfo['id']);
       echo '<pre>';
       print_r($res);
       echo '</pre>';
       die();
        $this->view->assign('list', $list);
        $this->view->display("company/run"); //使用模板
    }
  
}
