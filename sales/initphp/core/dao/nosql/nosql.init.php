<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*********************************************************************************
 * Dao-Nosql
***********************************************************************************/
class nosqlInit {

	private static $instance = array();  //单例模式获取nosql类
	private $nosql_type = array('MONGO', 'REDIS');
	
	/**
	 * 获取Nosql对象
	 * @param string $type
	 */
	public function init($type = 'MONGO', $server = 'default') {
		$InitPHP_conf = InitPHP::getConfig(); //需要设置文件缓存目录
		$type = strtoupper($type); 
		$type = (in_array($type, $this->nosql_type)) ? $type : 'MONGO';
		switch ($type) { 
			case 'MONGO' :
				$instance_name = 'mongo_' . $server;
				if (isset(nosqlInit::$instance[$instance_name])) return nosqlInit::$instance[$instance_name];
				$mongo = $this->load_nosql('mongo.init.php', 'mongoInit', $server);
				$mongo->init($InitPHP_conf['mongo'][$server]);
				nosqlInit::$instance[$instance_name] = $mongo;
				return $mongo;
				break;
			
			case 'REDIS' :
				$instance_name = 'redis_' . $server;
				if (isset(nosqlInit::$instance[$instance_name])) return nosqlInit::$instance[$instance_name];
				$redis = $this->load_nosql('redis.init.php', 'redisInit', $server);
				$redis->init($InitPHP_conf['redis'][$server]);
				nosqlInit::$instance[$instance_name] = $redis;
				return $redis;
				break;
		}
	}
	
	/**
	 * 加载不同NOSQL类文件
	 * @param  string $file  缓存文件名 
	 * @param  string $class 缓存类名
	 * @param  String $server 服务器
	 * @return obj
	 */
	private function load_nosql($file, $class, $server) {
		if (nosqlInit::$instance['require'][$file] != TRUE) {
			require('driver/' . $file);
			nosqlInit::$instance['require'][$file] = TRUE;
		}
		$tag = $class . "_" . $server;
		if (!nosqlInit::$instance['class'][$tag]) {
			nosqlInit::$instance['class'][$tag] = new $class;
			return nosqlInit::$instance['class'][$tag];
		} else {
			return nosqlInit::$instance['class'][$tag];
		}
	}
}