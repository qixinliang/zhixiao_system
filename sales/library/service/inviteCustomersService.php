<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*
 * @邀请用户service
 */
class inviteCustomersService extends Service{

	public $customerDao = NULL;
	public function __construct(){
		parent::__construct();
		$this->userYaoqingMalistDao = InitPHP::getDao("user_yaoqingma_list");
	}
    /************************************************************
     * @copyright(c): 2017年3月23日
     * @Author:  yuwen
     * @Create Time: 下午4:22:05
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @获取邀请用户UID列表
     *************************************************************/
	public function getInviteCustomersUidList($friends){
		return $this->userYaoqingMalistDao->getUidlist($friends);
	}
	/************************************************************
	 * @copyright(c): 2017年3月29日
	 * @Author:  yuwen
	 * @Create Time: 下午1:38:36
	 * @qq:32891873
	 * @email:fuyuwen88@126.com
	 * @获取邀请客户列表按时间段查询
	 *************************************************************/
	public function getAccessToCustomerThisMonth($uid){
	    $time = '2016-10-08 13:13:38';//date("Y-m-d H:i:s");
	    $firstday = date('Y-m-01', strtotime($time)).' 00:00:00';
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day")).' 23:59:59';
        $where = ' and add_date>='.strtotime($firstday).' and add_date<='.strtotime($lastday);
	    return $this->userYaoqingMalistDao->getUidlist($uid,$where);
	}
}
