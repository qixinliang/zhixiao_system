<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 管理员Dao
 * @author aaron
 */
class myClientsDao extends Dao
{
    
    public function getUserId($user){
        $sql = "select u.id,u.username from cp_zjingjiren_admin a left join cp_user u on a.`user` = u.username  where a.user = '$user'";
        return $this->dao->db->get_one_sql($sql);
    }
    
    public function getFriends($id,$limit,$where){
        $sql = "select d.deal_id,h.UsrName,h.UsrMp,d.title,o.order_money,o.OrdDate,d.start_date,d.end_date,o.`status`,d.expires_type,d.expires FROM cp_user_yaoqingma_list y left join cp_deal_order o on y.friends = o.uid left join cp_deal d on o.deal_id = d.deal_id right join cp_user_huifu h on y.friends = h.uid where y.uid = $id and o.`status`=2 $where limit $limit,10";
//        echo $sql;exit;
        return $this->dao->db->get_all_sql($sql);
    }
    public function getFriendsCount($id,$where){
        $sql = "select count(*) as count FROM cp_user_yaoqingma_list y left join cp_deal_order o on y.friends = o.uid left join cp_deal d on o.deal_id = d.deal_id right join cp_user_huifu h on y.friends = h.uid where y.uid = $id and o.`status`=2 $where";
        return $this->dao->db->get_one_sql($sql);
    }
}
