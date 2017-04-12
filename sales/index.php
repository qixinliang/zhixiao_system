<?php
define("APP_PATH", "./"); 
header("Content-Type:text/html; charset=utf-8");   
require_once(APP_PATH . 'initphp/initphp.php'); //导入配置文件-必须载入
require_once(APP_PATH . 'conf/comm.conf.php'); //公用配置
date_default_timezone_set('Asia/Shanghai');
ini_set('date.timezone','Asia/Shanghai');
InitPHP::init();
