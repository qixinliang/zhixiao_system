<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/*
 * @公告管理控制器
 */
class noticeController extends baseController{

	public $initphp_list = array('run','publish','publishSave');
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

		$this->view->assign('notice_list',$rows);
		$this->view->display('notice/run');
	}
	
	//发布公告
	public function publish(){
		//$title = $this->controller->get_gp('title');
		//$content = $this->controller->get_gp('content');
		$this->view->display('notice/publish');
	}
	public function publishSave(){
		$title = $this->controller->get_gp('title');
		$content = $this->controller->get_gp('content');

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
	
	public function edit(){

	}

	//根据ID删除一个公告
	public function del(){
	}
}
