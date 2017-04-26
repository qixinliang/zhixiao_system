<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * @公告管理控制器
 */
class noticeController extends baseController{

	public $initphp_list = array('info','run','publish','edit','editSave','del');
	public $noticeService = NULL;
	public $authService = NULL;

	public function __construct(){
		parent::__construct();
		$this->authService = InitPHP::getService('auth');
		$this->noticeService = InitPHP::getService('notice');
	}

	//公告详情
	public function info(){
		$notice_id = $this->controller->get_gp('id');
		$arr = $this->noticeService->getNoticeInfo($notice_id);
		$this->view->assign('arr', $arr);
		$this->view->display('notice/info');
	}

	//公告列表展示
	public function run(){
		$this->authService->checkauth('1029');
		//增加分页
        $pager = $this->getLibrary('pager');
        $page  = $this->controller->get_gp('page')?
                $this->controller->get_gp('page') : 1 ;
        if($page < 1) $page = 1;

        $offset = 10;

        $page   = ($page-1)*$offset? ($page-1)*$offset : 0;
		$rows = $this->noticeService->getNoticeList($page,$offset);
		/*
		if(!isset($rows) || empty($rows)){
			exit(json_encode(array('status' => -1,'message' => 'notice empty！')));
		}*/

		$count    =  $this->noticeService->getNoticeCount();
		$pageHtml = $pager->pager($count['count'],$offset,'/notice/run');
		$this->view->assign('count',$count['count']);
	
		//提供给首页最新的公告用下面的函数
		$latest = $this->noticeService->getLatestNotice();
		/*
		 * @判断当前用户是否有权限访问组织结构cai'dan
		 */
		$adminService = InitPHP::getService('admin');
		$userinfo = $adminService->current_user();
		$this->view->assign('gid', $userinfo['gid']);
		
		$this->view->assign('notice_list',$rows);
		
		$notice='yes';//默认加样式
		$this->view->assign('notice', $notice);
        $this->view->assign('page',$page);
        $this->view->assign('page_html', $pageHtml);
		
		
		//左侧样式是否显示高亮样式
		$noticeleftcorpnav = 'yes';
		$this->view->assign('noticeleftcorpnav', $noticeleftcorpnav);
		
		$this->view->display('notice/run');
	}
	
	//发布公告
	public function publish(){
		$this->authService->checkauth('1030');
		$title = $this->controller->get_gp('title');
		$content = $this->controller->get_gp('content');
		if(empty($title) || empty($content)){
		    /*
		     * @判断当前用户是否有权限访问组织结构cai'dan
		     */
		    $adminService = InitPHP::getService('admin');
		    $userinfo = $adminService->current_user();
		    $this->view->assign('gid', $userinfo['gid']);
		    $notice='yes';//默认加样式
		    $this->view->assign('notice', $notice);
		    //左侧样式是否显示高亮样式
		    $noticeleftcorpnav = 'yes';
		    $this->view->assign('noticeleftcorpnav', $noticeleftcorpnav);
		    
			$this->view->display('notice/publish');
		}else{
			$time = time();
			$data = array(
				'title' => $title,
				'content' => $content,
				'create_time' => $time,
				'update_time' => $time,
			);
			$ret = $this->noticeService->add($data);
			if($ret > 0){
				exit(json_encode(array('status'=> 1,'message' => '公告发布成功！')));
			}else{
				exit(json_encode(array('status'=> -1,'message' => '公告发布失败！')));
			}

		}
	}

	//公告修改
	public function edit(){
		$this->authService->checkauth('1031');
		$id = $this->controller->get_gp('id');
		$info = $this->noticeService->getNoticeInfo($id);
		if(!isset($info) || empty($info)){
			exit(json_encode(array('status' => -1,'message' => 'Notice not Found！')));
		}	
		$this->view->assign('info',$info);
		/*
		 * @判断当前用户是否有权限访问组织结构cai'dan
		 */
		$adminService = InitPHP::getService('admin');
		$userinfo = $adminService->current_user();
		$this->view->assign('gid', $userinfo['gid']);
		$notice='yes';//默认加样式
		$this->view->assign('notice', $notice);
		
		//左侧样式是否显示高亮样式
		$noticeleftcorpnav = 'yes';
		$this->view->assign('noticeleftcorpnav', $noticeleftcorpnav);
		
		$this->view->display('notice/edit');
	}

	public function editSave(){
		$this->authService->checkauth('1032');
		$noticeId = $this->controller->get_gp('notice_id');
		
		$title = $this->controller->get_gp('title');
		$content = $this->controller->get_gp('content');
		$data = array(
			'title' => $title,
			'content' => $content,
			'update_time' => time(),
		);

		$ret = $this->noticeService->update($data,$noticeId);
		if($ret > 0){
			exit(json_encode(array('status'=> 1,'message' => '公告修改成功！')));
		}else{
			exit(json_encode(array('status'=> -1,'message' => '公告修改失败！')));
		}
	}

	//根据ID删除一个公告
	public function del(){
		$this->authService->checkauth('1033');
		$noticeId = $this->controller->get_gp('id');		

		if(!isset($noticeId) || empty($noticeId)){
			exit(json_encode(array('status'=> -1,'message' => 'empty notice id！')));
		}
		$ret = $this->noticeService->del($noticeId);
		if($ret){
 			exit(json_encode(array('status' => 1, 'message' => '公告删除成功!')));
		}else{
		 	exit(json_encode(array('status' => -1, 'message' => '公告删除失败!')));	
		}
	}
}
