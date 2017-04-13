<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 我的客户管理Dao
 * @author aaron
 */
class myClientsDao extends Dao
{
    /**
     * 查询出投资的客户数据列表
     * @param type $friendIds
     * @param type $limit
     * @param type $where
     * @return array
     */
    public function getInvestFriends($uid,$where=null){
        $sql = "select u.id as uid,d.deal_id,h.UsrName,i.phone,d.title,o.order_money,o.order_time,d.start_date,d.end_date,d.syl,d.expires_type,d.expires,d.full_time,d.deal_id,o.VocherAmt,o.JiaXi from cp_deal d left join cp_deal_order o on d.deal_id = o.deal_id left join cp_user_yaoqingma_list y on o.uid = y.uid left join cp_user u on y.uid = u.id left join cp_user_huifu h on u.id =h.uid left join cp_user_info i on h.uid = i.uid where o.status=2 and y.friends = $uid $where ;";
        return $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 查询出未投资的客户数据
     * @param type $friendIds
     * @param type $limit
     * @param type $where
     * @return array
     */
    public function getNoInvestFriends($friendIds,$where=''){
        $sql = "select u.id,u.username,h.UsrName,h.UsrMp,u.login_time,h.create_time,i.phone from cp_user u left join cp_user_huifu h on u.id = h.uid left join cp_user_info i on u.id = i.uid where u.id in($friendIds) $where ";
        return $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 统计未投资客户数量
     * @param type $friendIds
     * @param type $where
     * @return type
     */
    public function getNoInvestFriendsCount($friendIds,$where=''){
        $sql = "select count(u.id) as count from cp_user u left join cp_user_huifu h on u.id = h.uid left join cp_user_info i on u.id = i.uid where u.id in($friendIds) $where ";
        return $this->dao->db->get_one_sql($sql);
    }
    /**
     * 统计投资客户数量
     * @param type $friendIds
     * @param type $where
     * @return type
     */
//    public function getInvestFriendsCount($uid,$where){
//        $sql = "select count(h.uid) as count from cp_user_yaoqingma_list y left join cp_user_huifu h on y.uid = h.uid left join cp_deal_order o on h.uid = o.uid left join cp_deal d on o.deal_id = d.deal_id left join cp_user_info i on h.uid = i.uid where y.friends = $uid $where";
//        return $this->dao->db->get_one_sql($sql);
//    }
    
    
    
    /**
     * 查询当前用户邀请的好友id列表
     * @param int $uid 用户id
     * @return array
     */
    public function getFriendsIdList($uid){
        $sql = "select uid from cp_user_yaoqingma_list where friends = $uid";
        return $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 根据邀请的好友id，查询订单表是
     * @param int $friendId 好友id
     * @return array
     */
    public function getFriednOorder($friendId){
        $sql = "select * from cp_deal_order where uid = $friendId";
        return $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 查询当前客户的邀请人是否已经离职，获取邀请人的基本信息
     * @param int clentId 客户id 
     * @return array
     */
    public function getInviterDeparture($clientId){
        $sql = "select z.department_id,u.id,y.add_date,u.username from cp_user_yaoqingma_list y left join cp_user u on y.friends = u.id left join zx_admin z on u.username = z.`user` where y.uid= $clientId";
        return $this->dao->db->get_one_sql($sql);
    }
    
    /**
     * 查询客户的原始邀请人信息
     * @param type $clientId
     * @return array
     */
    public function getoOriginalInviter($clientId){
        $sql = "select * from zx_customer_pool where investor_id = $clientId";
        return $this->dao->db->get_one_sql($sql);
    }
    
    /**
     * 查询客户分配后的邀请人信息
     * @param type $clientId
     * @return type
     */
    public function getAllocationInviter($clientId){
        $sql = "select u.id,u.username,r.create_time,i.phone from zx_customer_record r left join cp_user u on r.new_inviter_id = u.id left join cp_user_info i on u.id = i.uid where r.investor_id = $clientId";
        return $this->dao->db->get_all_sql($sql);
    }
    /**
     * 查询客户的详细信息
     * @param type $clientId
     * @return type
     */
    public function getClientInfo($clientId){
        $sql = "select h.UsrName,h.UsrMp,h.IdNo,h.create_time from cp_user u left join cp_user_huifu h on u.id = h.uid where u.id = $clientId";
        return $this->dao->db->get_one_sql($sql);
    }
    
    /**
     * 查询客户分配记录表里，分配给我的客户id
     * @param type $uid
     * @return type
     */
    public function getCustomerRecordList($uid){
        $sql="select * from zx_customer_record where principal=1 and new_inviter_id = $uid";
        return $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 查询客户分配表里面的客户订单信息
     * @param type $investor_id
     * @param type $where
     * @return type
     */
    public function getCustomerRecordOrder($investor_id,$where){
        $sql="select i.uid,d.deal_id,h.UsrName,i.phone,d.title,o.order_money,o.order_time,d.start_date,d.end_date,d.syl,d.expires_type,d.expires,d.full_time,d.deal_id,o.VocherAmt,o.JiaXi from cp_deal d ,cp_deal_order o ,cp_user_huifu h ,cp_user_info i where d.deal_id = o.deal_id and o.uid = h.uid and h.uid = i.uid and o.uid = $investor_id $where";
        return $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 根据用户id，统计用户邀请的客户数量
     * @param type $uid
     */
    public function clientCount($uid){
        $sql="select count(id) as count from cp_user_yaoqingma_list where friends = $uid";
        return $this->dao->db->get_one_sql($sql);
    }
    
    //从cp_user_yaoqing_list获取客户的业务员姓名
    public function getSalesmanUsername($clientId){
        $sql="select u.username from cp_user_yaoqingma_list y left join cp_user u on y.friends = u.id where y.uid = $clientId";
        return $this->dao->db->get_one_sql($sql);
    }
    
    public function getSalesmanUsername2($clientId){
        $sql="select new_inviter_name from zx_customer_record where principal=1 and investor_id = $clientId";
        return $this->dao->db->get_one_sql($sql);
    }
}
