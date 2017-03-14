<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*********************************************************************************
 * View-default 默认模板驱动规则模型
***********************************************************************************/
class defaultInit {

	/**
	 * 模板驱动-默认的驱动
	 * @param  string $str 模板文件数据
	 * @return string
	 */
	 public function init($str, $left, $right) {
	 	$pattern = array('/'.$left.'/', '/'.$right.'/');
		$replacement = array('<?php ', ' ?>');
		return preg_replace($pattern, $replacement, $str);
	 }
}
