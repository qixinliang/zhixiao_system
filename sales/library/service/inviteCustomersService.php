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
		return $this->userYaoqingMalistDao->friends_list($friends);
	}
}
