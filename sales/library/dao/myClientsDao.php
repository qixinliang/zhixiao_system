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
    public function getInvestFriends($friendIds,$page,$length,$where){
        $sql = "select h.uid,d.deal_id,h.UsrName,h.UsrMp,d.title,o.order_money,o.OrdDate,d.start_date,d.end_date,o.`status`,d.expires_type,d.expires from cp_user_huifu h left join cp_deal_order o on h.uid = o.uid left join cp_deal d on d.deal_id = o.deal_id where h.uid in ($friendIds) $where limit $page ,$length";
        return $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 查询出未投资的客户数据
     * @param type $friendIds
     * @param type $limit
     * @param type $where
     * @return array
     */
    public function getNoInvestFriends($friendIds,$page,$length,$where){
        $sql = "select u.id,u.username,h.UsrName,h.UsrMp,u.login_time,h.create_time from cp_user u left join cp_user_huifu h on u.id = h.uid where  u.id in($friendIds) $where limit $page ,$length";
        return $this->dao->db->get_all_sql($sql);
    }
    
    /**
     * 统计未投资客户数量
     * @param type $friendIds
     * @param type $where
     * @return type
     */
    public function getNoInvestFriendsCount($friendIds,$where){
        $sql = "select count(u.id) as count from cp_user u left join cp_user_huifu h on u.id = h.uid where  u.id in($friendIds) $where";
        return $this->dao->db->get_one_sql($sql);
    }
    /**
     * 统计投资客户数量
     * @param type $friendIds
     * @param type $where
     * @return type
     */
    public function getInvestFriendsCount($friendIds,$where){
        $sql = "select count(h.uid) as count from cp_user_huifu h left join cp_deal_order o on h.uid = o.uid left join cp_deal d on d.deal_id = o.deal_id where h.uid in ($friendIds) $where";
        return $this->dao->db->get_one_sql($sql);
    }
    
    
    
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
}
