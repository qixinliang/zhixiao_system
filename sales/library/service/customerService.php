<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

class customerService extends Service{

	public $customerDao = NULL;
	public function __construct(){
		parent::__construct();
		$this->customerDao = InitPHP::getDao("customer");
	}

	public function addCustomer($salesId){
		return $this->customerDao->add($salesId);
	}

	public function getCustomers($where){
		return $this->customerDao->getCustomers($where);
	}
}
