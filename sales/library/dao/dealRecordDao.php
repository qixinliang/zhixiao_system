<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 回款查询Dao
 * @author aaron
 */
class dealRecordDao extends Dao{
    
    public $table_name = 'cp_deal_record';
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午4:55:11
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @根据uid和条件查询回款记录
     *************************************************************/
    public function getUserReceivableOrderList($uid,$where){
        $sql=sprintf("select * from %s where status=2 and uid='%s' % ",$this->table_name,$uid,$where);
        return $this->dao->db->get_all_sql($sql);
    }
}