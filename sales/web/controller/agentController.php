<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/************************************************************
 * @copyright(c): 2016年12月14日
 * @Author:  yuwen
 * @Create Time: 上午12:37:05
 * @qq:32891873
 * @email:fuyuwen88@126.com
 * @经纪人业务处理
 *************************************************************/
class agentController extends baseController{
    
    public $initphp_list = array('addshow','add_save','edit','edit_save','del'); //Action白名单
    
    public function __construct(){
        parent::__construct();
        $this->adminService = InitPHP::getService("admin");     //获取Service
        $this->authService = InitPHP::getService("auth");       //获取权限Service
        $this->agentService = InitPHP::getService("agent");     //经纪人Service
    }
    /************************************************************
     * @copyright(c): 2016年12月14日
     * @Author:  yuwen
     * @Create Time: 上午12:41:16
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @经纪人添加投资人页面显示
     *************************************************************/
    public function addshow(){
        $this->authService->checkauth("1030");
        $jingjireninfo = $this->adminService->current_user();    //获取当前登录经济人信息
        $this->view->assign('action_name', '添加投资人');          //时间名称添加投资人
        $this->view->assign('action', 'add');                    //添加action事件  
        $this->view->assign('jingjireninfo', $jingjireninfo);    //数据送入模板
        $this->view->display("agent/addshow");                   //显示添加投资人页面
    }
    /************************************************************
     * @copyright(c): 2016年12月14日
     * @Author:  yuwen
     * @Create Time: 上午12:43:52
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @经纪人保存投资人信息
     *************************************************************/
    public function add_save(){
        if($this->authService->checkauthUser("1030")==false){
            die(json_encode(array('status' => 0, 'message' => '您没有权限')));
        }
        $token = $this->controller->check_token();
        if(!$token){
            exit();//如果token不存在则退出
        }
        $POST['yaoqingma']  = $this->controller->get_gp('yaoqingma');       //邀请码
        $POST['password']   = $this->controller->get_gp('password');        //密码
        $POST['rePassword'] = $this->controller->get_gp('rePassword');      //确认密码
        $POST['phone']      = $this->controller->get_gp('phone');           //手机号
        $POST['user']       = $this->controller->get_gp('user');            //用户名
		$POST['laiyuan']    = $this->controller->get_gp('laiyuan');         //来源
		$info = $this->agentService->add_save($POST);
		switch ($info){
		    case '1':
		      $retrun = json_encode(array('status' => 1, 'message' => '两次密码输入不同'));
		      break;
		    case "123":   //用户名必须要6-16位字母、数字和下划线
		      $retrun = json_encode(array('status' => 123, 'message' => '用户名必须要6-16位字母、数字和下划线'));
		      break;
		    case "234":   //用户名只能包含数字、字母、下划线，不能使用特殊字符
		      $retrun = json_encode(array('status' => 234, 'message' => '用户名只能包含数字、字母、下划线，不能使用特殊字符'));
		      break;
		    case "345":   //用户名不能为手机号
		      $retrun = json_encode(array('status' => 345, 'message' => '用户名不能为手机号'));
		      break;
		    case "456":   //用户名已经存在
		      $retrun = json_encode(array('status' => 456, 'message' => '用户名已经存在'));
		      break;
		    case "567":   //手机号已存在
		      $retrun = json_encode(array('status' => 567, 'message' => '手机号已存在'));
		      break;
		    case "678":   //成功
		      $retrun = json_encode(array('status' => 678, 'message' => '添加成功'));
		      break;
		      default:
		      $retrun = json_encode(array('status' => 679, 'message' => '添加失败'));
		      break;
		}
		die($retrun);
    }
}