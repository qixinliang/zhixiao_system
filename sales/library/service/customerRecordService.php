<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*
 * @客户分配记录服务层
 */
class customerRecordService extends Service{
	public $customerRecordDao = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->customerRecordDao = InitPHP::getDao('customerRecord');
	}
    public function getRecords($page,$offset,$where){
        return $this->customerRecordDao->getRecords($page,$offset,$where);
    }

    public function getRecordsCount($where){
        return $this->customerRecordDao->getRecordsCount($where);
    }

	public function addSave($data){
		return $this->customerRecordDao->addSave($data);
	}
}
