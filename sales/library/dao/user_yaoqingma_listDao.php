<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 用户奖励邀请码表Dao
 * @author aaron
 */
class user_yaoqingma_listDao extends Dao
{
    private $table_name = 'cp_user_yaoqingma_list';
    //根据邀请人id取出数
    public function friends_count($uid)
    {
        $sql=sprintf("select count(*) as num from %s where friends='%s'",$this->table_name,$uid);
        $data = $this->dao->db->get_one_sql($sql);
        return $data['num'];
    }
    /*
     * @获取被邀请的用户id
     */
    public function friends_list($uid)
    {
        $sql=sprintf("select * from %s a , %s b where a.uid=b.uid and a.friends='%s'",$this->table_name,'cp_deal_order',$uid);
        return $this->dao->db->get_all_sql($sql);
    }
    /************************************************************
     * @copyright(c): 2016年12月20日
     * @Author:  yuwen
     * @Create Time: 下午12:02:43
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @根据邀请人id查询用户id
     *************************************************************/
    public function getUidlist($uid){
        $sql=sprintf("select `uid` from %s where friends='%s'",$this->table_name,$uid);
        $data = $this->dao->db->get_all_sql($sql);
        return $data;
    }
}