<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*********************************************************************************
 * 工具库-phpinfo
***********************************************************************************/
class phpinfoInit {
	
	/**
	 * 显示PHPINFO信息
	 * 使用方法：$this->getUtil('phpinfo')->get_phpinfo();
	 */
	public function get_phpinfo() {
		phpinfo();  
		exit;
	}
}