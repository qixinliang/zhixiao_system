<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

class customerService extends Service{

	public $customerDao = NULL;
	public function __construct(){
		parent::__construct();
		$this->customerDao = InitPHP::getDao("customer");
	}
    /************************************************************
     * @copyright(c): 2017年3月23日
     * @Author:  yuwen
     * @Create Time: 下午4:22:05
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @删除用户写入公共池方法使用
     *************************************************************/
	public function addCustomer($salesId){
		return $this->customerDao->add($salesId);
	}

	public function getCustomers($where){
		return $this->customerDao->getCustomers($where);
	}
	
	public function getCustomers2($page,$offset,$where){
		return $this->customerDao->getCustomers2($page,$offset,$where);
	}

	public function getCustomers2Count($where){
		return $this->customerDao->getCustomers2Count($where);
	}
}
