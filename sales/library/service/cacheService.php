<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

class cacheService extends Service{

	public $cacheDao = NULL;
	public function __construct(){
		parent::__construct();
		$this->cacheDao = InitPHP::getDao('cache');
	}

	public function cacheSet($k,$v){
		$this->cacheDao->cacheSet($k,$v);
	}
	
	public function cacheGet($k){
		return $this->cacheDao->cacheGet($k);
	}

	public function cacheClear($k){
		$this->cacheDao->cacheClear($k);
	}
}
