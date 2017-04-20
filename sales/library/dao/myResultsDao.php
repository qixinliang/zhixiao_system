<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 我的业绩
 * @author aaron
 */
class myResultsDao extends Dao{
    
    public $table_name = 'cp_deal_order';
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午4:55:11
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @获取用户投资记录包含投资项目
     *************************************************************/
    public function getUserOrderList($uid,$where){
        $sql=sprintf("select a.*,b.title,b.syl,b.expires_type,b.expires,b.deal_status from %s as a LEFT JOIN %s as b ON b.deal_id=a.deal_id where a.uid=%s and a.status=2  %s ",$this->table_name,'cp_deal',$uid,$where);
        return $this->dao->db->get_all_sql($sql);
    }
}