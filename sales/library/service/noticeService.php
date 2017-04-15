<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*
 * @公告管理服务层
 */
class noticeService extends Service{

	public $noticeDao = NULL;	
	public function __construct(){
		parent::__construct();
		$this->noticeDao = InitPHP::getDao('notice');
	}

	//公告列表
	public function getNoticeList(){
		return $this->noticeDao->getNoticeList();
	}

	//发布
	public function add($data){
		return $this->noticeDao->add($data);
	}
}
