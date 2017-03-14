<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*********************************************************************************
 * 工具库-sql监控  
***********************************************************************************/
class sqlcontrolInit { 
	
	/**
 	 * 数据库语句监控器-开始点
 	 * 使用方法：$this->getUtil('sqlcontrol')->start();
 	 * @return string   
	 */
	public function start() {
		InitPHP::setConfig('issqlcontrol', 1);		
	}
	
	/**
 	 * 数据库语句监控器-结束点
  	 * 使用方法：$this->getUtil('sqlcontrol')->end();
 	 * @return string   
	 */
	public function end() {
		$InitPHP_conf = InitPHP::getConfig();
		if (isset($InitPHP_conf['sqlcontrolarr']) && is_array($InitPHP_conf['sqlcontrolarr'])) {
			$i = 1;
			echo '<div style=" border:1px #000000 dotted; width:100%; background-color:#EEEEFF">';
			foreach ($InitPHP_conf['sqlcontrolarr'] as $k => $v) {
				echo '<div style=" height:20px; text-align:left; font-size:14px; margin-left:10px;margin-top:5px;"><span>'.$i.'.&nbsp;&nbsp;&nbsp;&nbsp;</span>' . $v . '</div>';
				$i++;
			}
			echo '</div>';
		}
	}
}