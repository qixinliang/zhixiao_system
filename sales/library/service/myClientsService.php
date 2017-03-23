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
     * 根据用户名称，查询user数据列表id
     * @param type $user
     * @return type
     */
    public function getUserId($user){
        return $this->myClientsDao->getUserId($user);
    }
    
    /**
     * 根据条件查询投资客户数据列表
     * @param type $friendIds 好友id
     * @param type $page 开始页码
     * @param type $length 长度
     * @param type $where 查询条件
     * @return type
     */
    public function getInvestFriends($friendIds,$page,$length,$where){
        return $this->myClientsDao->getInvestFriends($friendIds,$page,$length,$where);
    }
    
    /**
     * 根据条件查询未投资客户数据列表
     * @param type $friendIds 好友id
     * @param type $page 开始页码
     * @param type $length 长度
     * @param type $where 查询条件
     * @return type
     */
    public function getNoInvestFriends($friendIds,$page,$length,$where){
        return $this->myClientsDao->getNoInvestFriends($friendIds,$page,$length,$where);
    }
    
    /**
     * 统计投资客户数量
     * @param type $id
     * @param type $where
     * @return array
     */
    public function getInvestFriendsCount($id,$where){
        return $this->myClientsDao->getInvestFriendsCount($id,$where);
    }
    
    /**
     * 统计未投资客户数量
     * @param type $id
     * @param type $where
     * @return array
     */
    public function getNoInvestFriendsCount($id,$where){
        return $this->myClientsDao->getNoInvestFriendsCount($id,$where);
    }
    
    /**
     * 获取用户邀请的好友id列表
     * @param int $uid 用户id
     * @param int $status 返回的是投资好友id或未投资好友id
     */
    public function getfriendsIdList($uid,$status){
        $noInvest = '';
        $yesInvest = '';
            //根绝当前登录用户，获取邀请过的好友id
            $friends = $this->myClientsDao->getFriendsIdList($uid);
            if(empty($friends) || !isset($friends)){
                exit('还没有邀请客户！');
            }
            //foreach循环判断用户是否投资
            foreach ($friends as $k=>$val){
                //根据邀请过的好友id，查询order表是否为空
                $friednOorder = $this->myClientsDao->getFriednOorder($val['uid']);
                if(empty($friednOorder)){
                    $noInvest = $noInvest.",".$val['uid'];
                }else{
                    $yesInvest = $yesInvest.",".$val['uid'];
                }
            }
            //判断显示类型，1表示未投资，返回未投资用户id，0表示投资，返回投资用户id
            if($status=='1'){
                $noInvest = substr($noInvest,1);
                $invest = $noInvest;
            }else{
                $yesInvest = substr($yesInvest,1);
                 $invest = $yesInvest;
            }
            return $invest;
    }
    
    /**
     * 根据检索条件，拼接where条件，和url链接地址
     * @param string $uname 用户名称
     * @param string $phone 手机号
     * @param string $start_date 开始时间
     * @param string $end_date 结束时间
     * @return  array 拼接的where条件，和url地址
     */
    public function arrange_where_url($uname='',$phone='',$start_date='',$end_date=''){
        $where = ' ';
        //分页地址
        $url = 'myClients/run';
        if($uname!=''){
            $url=$url.'/uname/'.$uname;
            $where.= ' and h.UsrName = "'.$uname.'"';
        }
        if($phone!=''){
            $url=$url.'/phone/'.$phone;
            $where.= ' and h.UsrMp = '.$phone;
        }
        if($start_date!=''){
            $url=$url.'/start_date/'.$start_date;
            $where.= ' and d.start_date >= '.strtotime($start_date);
        }
        if($end_date!=''){
            $url=$url.'/end_date/'.$end_date;
            $where.= ' and d.end_date <= '.strtotime($end_date);
        }
        
        return array('url'=>$url,'where'=>$where);
    }
}
