<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 经纪人添加投资人业务处理
 * @author aaron
 */
class agentService extends Service
{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 在投资系统中通过接口添加投资人账户
     */
    public function add_save($data)
    {
		if($data['password']<>$data['rePassword']){
		    return "1";
		}
        $data['password']=md5($data['password']);
		$data['regtime']  = time();
		//接口注册投资系统
		$curl = $this->getLibrary('curl'); 
		$url = "http://api.baihedai.com.cn/yeWuXiTong/register/?t=".json_encode($data);
		return $curl->get($url);
    }
}