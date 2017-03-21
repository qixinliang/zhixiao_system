<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 我的客户业务层
 * @author aaron
 */
class myClientsService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->myClientsDao = InitPHP::getDao("myClients");
    }
    
    public function getUserId($user){
        return $this->myClientsDao->getUserId($user);
    }
    
    public function getFriends($id,$limit,$where){
        return $this->myClientsDao->getFriends($id,$limit,$where);
    }
    
    public function getFriendsCount($id,$where){
        return $this->myClientsDao->getFriendsCount($id,$where);
    }
}