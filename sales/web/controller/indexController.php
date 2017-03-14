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
        $this->adminService = InitPHP::getService("admin");         //获取Service
        $this->TJHZService = InitPHP::getService("TJHZ");         //获取Service
    }
	public $initphp_list = array('home');

	/**
	 * 默认action
	 */
	public function run()
    {
        $list = $this->adminService->current_user();
        $this->view->assign('gid', $list['gid']);
        $this->view->assign('list', $list);
        $this->view->display("index/run");
	}

    /**
     * 欢迎页
     */
    public function home()
    {
        
        $userinfo = $this->adminService->current_user();
        /*
         * @今天的统计结果
         * @start
         */
        $rest = $this->TJHZService->JinTianJingJiRenList($userinfo);
        $this->view->assign('renshu', $rest['renshu']);
        $this->view->assign('znhjye', $rest['znhjye']);
        $this->view->assign('touzizongCout', $rest['touzizongCout']);
        /*
         * @今天的统计结果
         * @end
         */
         /*
          *@昨天的统计结果
          *@start
          */
        $beginrest = $this->TJHZService->ZuoTianJingJiRenList($userinfo);
        $this->view->assign('zrenshu', $beginrest['renshu']);
        $this->view->assign('zznhjye', $beginrest['znhjye']);
        $this->view->assign('ztouzizongCout', $beginrest['touzizongCout']);
        /*
         *@昨天的统计结果
         *@end
         */
        /*
         * @今天经纪人总数
         * @start
         */
        $jingjirenzongshu = $this->TJHZService->JinRiJingJIRenZongShu($userinfo);
        $this->view->assign('jingjirenzongshu', $jingjirenzongshu);
        /*
         * @今天经纪人总数
         * @end
         */
        $this->view->assign('list', $userinfo);
        
        $this->view->display("index/home");
    }
}
