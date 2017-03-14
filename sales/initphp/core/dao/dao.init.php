<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
class daoInit{
	
	/**
	 * @var dbInit
	 */
	public $db = NULL;   
	
	/**
	 * @var cacheInit
	 */
	public $cache = NULL;  
	
	/**
	 * @var nosqlInit
	 */
	public $nosql = NULL;
	
	/**
	 * 运行数据库
	 * 1. 初始化DB类  DAO中调用方法    $this->dao->db
	 */
	public function run_db() {
		if ($this->db == NULL) {  
			require(INITPHP_PATH . "/core/dao/db/db.init.php");  
			$this->db = InitPHP::loadclass('dbInit');
			$this->db->init_db('');
		}
		return $this->db;
	}
	
	/**
	 * 运行缓存模型
	 * 1. 初始化cache类  DAO中调用方法    $this->dao->cache
	 */
	public function run_cache() {
		if ($this->cache == NULL) {
			require(INITPHP_PATH . "/core/dao/cache/cache.init.php");
			$this->cache = InitPHP::loadclass('cacheInit');	
			$this->cache->db_handle = $this->db; //db对象,MYSQL缓存中需要用到
		}
		return $this->cache;
	}
	
	/**
	 * 运行nosql
	 * $this->getNosql()
	 */
	public function run_nosql() {
		if ($this->nosql == NULL) {
			require(INITPHP_PATH . "/core/dao/nosql/nosql.init.php");
			$this->nosql = InitPHP::loadclass('nosqlInit');	
		}
		return $this->nosql;
	}
	 
}
