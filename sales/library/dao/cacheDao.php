<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');


//缓存DAO
class cacheDao extends Dao{

	public function cacheSet($k = 'test',$v = ''){
		$this->dao->cache->set($k,$v,0,'FILE');
	}

	public function cacheGet($k = 'test'){
		return $this->dao->cache->get($k,'FILE');
	}

	public function cacheClear($k = 'test'){
		$this->dao->cache->clear($k,'FILE');
	}
}
