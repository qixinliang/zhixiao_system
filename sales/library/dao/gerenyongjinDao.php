<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 个人佣金统计Dao
 * @author aaron
 */
class gerenyongjinDao extends Dao
{
    public $table_name = 'zx_admin';
    
    /************************************************************
     * @copyright(c): 2016年12月16日
     * @Author:  yuwen
     * @Create Time: 下午3:34:25
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @个人佣金统计列表经纪人或总监自己推荐的客户
     *************************************************************/
    public function gerenyongjin_list($uid,$sqlwhere=null,$limit=null){
        if($sqlwhere)
        {
            $sqlwhere=" and ".$sqlwhere;
        }
        if(!empty($limit)){
            $limit = ' limit '.$limit;
        }
        $sql=sprintf("select a.UsrName as TuiJianName,e.username as TouZiRen,d.uid,e.id as delal_order_id ,e.deal_number,e.order_money,e.`status`,e.create_time,f.jiangli_type,f.tiexi,e.JiaXi,e.VocherAmt,f.title,f.syl from %s as a left JOIN zx_role as b on a.gid=b.id left JOIN cp_user as c ON c.username=a.`user` left join cp_user_yaoqingma_list as d ON d.friends=c.id left JOIN cp_deal_order as e ON e.uid=d.uid left join cp_deal as f on f.deal_id=e.deal_id WHERE a.id=%s and e.`status`=2 %s %s ",$this->table_name,$uid,$sqlwhere,$limit);
        return  $this->dao->db->get_all_sql($sql);
    }
    
    /************************************************************
     * @copyright(c): 2016年12月16日
     * @Author:  yuwen
     * @Create Time: 下午3:34:25
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @个人佣金统计列表经纪人或总监自己推荐的客户总数
     *************************************************************/
    public function gerenyongjin_count($uid,$sqlwhere=null,$limit=null){
        if($sqlwhere)
        {
            $sqlwhere=" and ".$sqlwhere;
        }
        $sql=sprintf("select count(e.deal_number) as number from %s as a left JOIN zx_role as b on a.gid=b.id left JOIN cp_user as c ON c.username=a.`user` left join cp_user_yaoqingma_list as d ON d.friends=c.id left JOIN cp_deal_order as e ON e.uid=d.uid left join cp_deal as f on f.deal_id=e.deal_id WHERE a.id=%s and e.`status`=2 %s %s ",$this->table_name,$uid,$sqlwhere,$limit);
        return  $this->dao->db->get_one_sql($sql);
    }
}