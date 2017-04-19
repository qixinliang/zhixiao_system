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
    
    /**
     * 根据条件查询投资客户数据列表
     * @param type $friendId 好友id
     * @param type $page 开始页码
     * @param type $length 长度
     * @param type $where 查询条件
     * @return type
     */
    public function getInvestFriends($uid,$where=null){
        return $this->myClientsDao->getInvestFriends($uid,$where);
    }
    
    /**
     * 根据条件查询未投资客户数据列表
     * @param type $friendIds 好友id
     * @param type $page 开始页码
     * @param type $length 长度
     * @param type $where 查询条件
     * @return type
     */
    public function getNoInvestFriends($friendIds,$where=null){
        return $this->myClientsDao->getNoInvestFriends($friendIds,$where);
    }
    
    /**
     * 统计投资客户数量
     * @param type $id
     * @param type $where
     * @return array
     */
    public function getInvestFriendsCount($uid,$where=null){
        return $this->myClientsDao->getInvestFriendsCount($uid,$where);
    }
    
    /**
     * 统计未投资客户数量
     * @param type $id
     * @param type $where
     * @return array
     */
    public function getNoInvestFriendsCount($id,$where=null){
        return $this->myClientsDao->getNoInvestFriendsCount($id,$where);
    }
    
    /**
     * 获取用户邀请的好友id列表
     * @param int $uid 用户id
     */
    public function getfriendsIdList($uid,$allocation){
        $noInvest = null;
        //根绝当前登录用户，获取邀请过的好友id
        $friends = $this->myClientsDao->getFriendsIdList($uid);
        if(empty($friends) || !isset($friends)){
            return $invest = ''; exit;
        }
        //foreach循环判断用户是否投资
        foreach ($friends as $k=>$val){
            //根据邀请过的好友id，查询order表是否为空
            $friednOorder = $this->myClientsDao->getFriednOorder($val['uid']);
            if(empty($friednOorder)){
                $noInvest= ','.$val['uid'];
            }
        }
        //循环分配记录表里面，分配给当前用户的客户信息，判断是否投资
        if(!empty($allocation)){
            foreach ($allocation as $k=>$v){
                $friednOorder = $this->myClientsDao->getFriednOorder($v['investor_id']);
                if(empty($friednOorder)){
                    $noInvest.=','.$v['investor_id'];
                }
            }
        }
        //判断显示类型，1表示未投资，返回未投资用户id，0表示投资，返回投资用户id
        $noInvest = substr($noInvest, 1);
        $invest = $noInvest;
        return $invest;
    }
    
    
    /**
     * 根据客户id，查询原邀请人信息状态
     * @param int $clientId 客户id
     * @return array
     */
    public function getInviterDeparture($inviterId){
        return $this->myClientsDao->getInviterDeparture($inviterId);
    }

    /**
     * 查询客户分配后的邀请人信息
     * @param type $clientId
     * @return type
     */
    public function getAllocationInviter($clientId){
        return $this->myClientsDao->getAllocationInviter($clientId);
    }
    
    /**
     * 查询客户的详细信息
     * @param type $clientId
     * @return type
     */
    public function getClientInfo($clientId){
        return $this->myClientsDao->getClientInfo($clientId);
    }
    
    /**
     * 查询当前客户是否投资
     * @param type $clientId 客户id
     * @return int 0未投资,1投资
     */
    public function getFriednOorder($clientId){
        $info = $this->myClientsDao->getFriednOorder($clientId);
        if(isset($info) && !empty($info)){
            return 1;
        }else{
            return 0;
        }
    }
    
    /**
     * 查询客户分配记录表里，分配给我的客户id
     * @param type $uid
     * @return type
     */
    public function getCustomerRecordList($uid){
        return $this->myClientsDao->getCustomerRecordList($uid);
    }
    
    /**
     * 查询客户分配表里面的客户订单信息
     * @param type $investor_id
     * @return array
     */
    public function getCustomerRecordOrder($investor_id,$where=null){
        return $this->myClientsDao->getCustomerRecordOrder($investor_id,$where);
    }
    
    /**
     * 根据用户id，统计用户邀请的客户数量
     * @param type $uid
     * @return type
     */
    public function clientCount($uid){
        return $this->myClientsDao->clientCount($uid);
    }
    
    /**
     * 循环取出客户分配表里面，分配给我的客户信息，并和我的客户数据合并
     * @param type $friends
     * @param type $customerRecordList
     * @param type $arrange_where_url
     * @return type
     */
    public function mergeData($friends,$customerRecordList,$where=null){
        foreach ($friends as $k=>$v){
            foreach($customerRecordList as $k1=>$v1){
                if($v['uid']==$v1['investor_id']){
                    unset($customerRecordList[$k1]);
                }
            }
        }
        foreach($customerRecordList as $k=>$v){
            //根据客户分配表里面的客户id，查询相关投资记录
            $customerRecordOrder = $this->getCustomerRecordOrder($v['investor_id'],$where);
            //循环查询当前客户所归属的业务员
            foreach ($customerRecordOrder as $k1=>$v1){
                $salesman = $this->myClientsDao->getSalesmanUsername2($v1['uid']);
                $customerRecordOrder[$k1]['salesman'] = $salesman['new_inviter_name'];
            }
            if(is_array($customerRecordOrder)){
                $friends = array_merge($friends,$customerRecordOrder);
            }
        }
        $customerFriendsCount = count($customerRecordList);//分配给我的邀请人数量，用于计算客户数量
        return array('friends'=>$friends,'customerFriendsCount'=>$customerFriendsCount);
    }
    
    public function getSalesmanUsername($uid){
        $salesman = $this->myClientsDao->getSalesmanUsername($uid);
        return $salesman['username'];
    }
    
    /**
     * 根据客户id，获取邀请人id
     * @param unknown $clientId
     * @return unknown
     */
    public function getInviter($clientId){
        return $this->myClientsDao->getInviter($clientId);
    }
    
}
