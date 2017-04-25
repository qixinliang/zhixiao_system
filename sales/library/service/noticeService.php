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
	public function getNoticeList($limit,$offset){
		return $this->noticeDao->getNoticeList($limit,$offset);
	}

	public function getNoticeCount(){
		return $this->noticeDao->getNoticeCount();
	}

	//发布
	public function add($data){
		return $this->noticeDao->add($data);
	}
	
	//根据id获取公告详情
	public function getNoticeInfo($id){
		return $this->noticeDao->getNoticeInfo($id);
	}


	//根据ID更新数据
	public function update($data,$id){
		return $this->noticeDao->update($data,$id);
	}

	//删除一条公告
	public function del($id){
		return $this->noticeDao->del($id);
	}

	//获取最新的公告
	public function getLatestNotice(){
		return $this->noticeDao->getLatestNotice();
	}
}
