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

	public function getCustomers($limit,$offset,$where){
		return $this->customerDao->getCustomers($limit,$offset,$where);
	}

	public function getCustomersCount($where){
		return $this->customerDao->getCustomersCount($where);
	}

	public function info($id){
		return $this->customerDao->info($id);
	}
	public function del($id){
		return $this->customerDao->del($id);
	}
}
