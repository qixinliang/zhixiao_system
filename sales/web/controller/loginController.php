<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 后台登录控制器
 * @author aaron
 */
class loginController extends Controller
{
	public $initphp_list = array('check','logout'); //Action白名单

	/**
	 * 默认action
	 */
	public function run()
    {
        $this->view->display("login/run"); //使用模板
	}

    /**
     * 检测用户提交的资料
     */
    public function check()
    {
        $token = $this->controller->check_token();
        if(!$token) $this->controller->redirect("/login/run");
        $user = $this->controller->get_gp('user');
        $password = md5($this->controller->get_gp('password'));
        $ip = $this->controller->get_ip();
        $loginService = InitPHP::getService("login");//获取Service
        $check = $loginService->check($user,$password,$ip);
        if(!$check)
        {
            exit(json_encode(array('status' => 0, 'message' => '帐号信息输入错误!')));
        }
        $session = $this->getUtil('session');
        $session->set('admin_id', $check['id']);
        $session->set('admin_user', $check['user']);
        $session->set('admin_nicename', $check['nicename']);
        $session->set('admin_logintime', $check['logintime']);
        $session->set('admin_ip', $ip);
        echo json_encode(array('status' => 1, 'message' => '登录成功!'));
    }

    //退出
    public function logout()
    {
        $session = $this->getUtil('session');
        $session->clear(); //清除session
        exit(json_encode(array('status' => 0, 'message' => '退出成功!')));
    }
}
