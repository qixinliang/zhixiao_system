<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * @公告管理控制器
 */
class noticeController extends baseController{

	public $initphp_list = array('run','publish','edit','editSave','del');
	public $noticeService = NULL;

	public function __construct(){
		parent::__construct();
		$this->noticeService = InitPHP::getService('notice');
	}

	//公告列表展示
	public function run(){
		$rows = $this->noticeService->getNoticeList();
		/*
		if(!isset($rows) || empty($rows)){
			exit(json_encode(array('status' => -1,'message' => 'notice empty！')));
		}*/
		$latest = $this->noticeService->getLatestNotice();
		var_dump($latest);

		$this->view->assign('notice_list',$rows);
		$this->view->display('notice/run');
	}
	
	//发布公告
	public function publish(){
		$title = $this->controller->get_gp('title');
		$content = $this->controller->get_gp('content');
		if(empty($title) || empty($content)){
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
		$id = $this->controller->get_gp('id');
		$info = $this->noticeService->getNoticeInfo($id);
		if(!isset($info) || empty($info)){
			exit(json_encode(array('status' => -1,'message' => 'Notice not Found！')));
		}	
		$this->view->assign('info',$info);
		$this->view->display('notice/edit');
	}

	public function editSave(){
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
