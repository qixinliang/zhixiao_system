<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*********************************************************************************
 * View-simple 简单模板驱动规则模型
***********************************************************************************/
class simpleInit {

	/**
	 * 模板驱动-简单的驱动
	 * @param  string $str 模板文件数据
	 * @return string
	 */
	 public function init($str, $left, $right) {
	 	//if操作
	 	$str = preg_replace( "/".$left."if([^{]+?)".$right."/", "<?php if \\1 { ?>", $str );
		$str = preg_replace( "/".$left."else".$right."/", "<?php } else { ?>", $str );
		$str = preg_replace( "/".$left."elseif([^{]+?)".$right."/", "<?php } elseif \\1 { ?>", $str );
		//foreach操作
		$str = preg_replace("/".$left."foreach([^{]+?)".$right."/","<?php foreach \\1 { ?>",$str);
		$str = preg_replace("/".$left."\/foreach".$right."/","<?php } ?>",$str);
		//for操作
		$str = preg_replace("/".$left."for([^{]+?)".$right."/","<?php for \\1 { ?>",$str);
		$str = preg_replace("/".$left."\/for".$right."/","<?php } ?>",$str);
		//输出变量
		$str = preg_replace( "/".$left."(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\'\"]*)".$right."/", "<?php echo \\1;?>", $str );
		//常量输出
		$str = preg_replace( "/".$left."([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)".$right."/s", "<?php echo \\1;?>", $str );
		//标签解析
		$str = preg_replace ( "/".$left."\/if".$right."/", "<?php } ?>", $str );
	 	$pattern = array('/'.$left.'/', '/'.$right.'/');
		$replacement = array('<?php ', ' ?>');
		return preg_replace($pattern, $replacement, $str);
	 }
}
