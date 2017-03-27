<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/************************************************************
 * @copyright(c): 2017年2月27日
 * @Author:  yuwen
 * @Create Time: 下午5:33:12
 * @qq:32891873
 * @email:fuyuwen88@126.com
 * @我的业绩
 *************************************************************/
class myResultsService extends Service{
    
    public function __construct(){
        parent::__construct();
    }
    /************************************************************
     * @copyright(c): 2017年3月21日
     * @Author:  yuwen
     * @Create Time: 上午10:00:25
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @我的业绩列表
     *************************************************************/
    public function ResultsList($userinfo){
        $adminService = InitPHP::getService("admin");//获取管理员信息
        $data = array();
        $yuefenarr = $this->returnnianfen($userinfo);
        $uid = $adminService->GetToZiXiTongUserId($userinfo['id']);//获取当前登录的用户uid
        foreach ($yuefenarr as $key=>$val){
            $data[$key] =$this->MonthlyPersonalDetail($uid,$val);
        }
        return array('data'=>$data,'NY'=>$yuefenarr);
    }
    /************************************************************
     * @copyright(c): 2017年3月21日
     * @Author:  yuwen
     * @Create Time: 下午1:41:07
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @月度个人明细
     * @统计当前登录用户自己投资的记录
     * @得到当前登录用户邀请客户投资的记录-------------此条去掉
     *************************************************************/
    public function MonthlyPersonalDetail($uid,$val){
        $myResultsDao = InitPHP::getDao("myResults");
        $useryaoqingmalistDao = InitPHP::getDao("user_yaoqingma_list");
        $where = ' and a.create_time>='.strtotime($val['start']).' and a.create_time<='.strtotime($val['end']);
        //$where=null;
        //获取登录用户自己的订单记录
        $userlist = $myResultsDao->getUserOrderList($uid,$where);
        $Userarr = $this->calculateData($userlist);
        /*
         * 获取被邀请客户的Uid列表
         * uid作为 friends
         */
        $yaoqingUserUidlist = $this->getYaoQingRenList($uid,$val);
        //得到邀请用户的全部订单
        //$yaoqingUserOrderList = $this->getYaoQingUserOrderList($yaoqingUserUidlist,$val);
        //$YaoQingUserarr = $this->calculateData($yaoqingUserOrderList);
        
        //获取邀请客户的回款投资记录
        //$yaoqingUserReceivabeOrderList =  $this->getUserReceivableOrderList($yaoqingUserUidlist,$val);
        
        //计算邀请的用户回款总额度
        //$yaoqingUserReceivabe = $this->ReceivableOrderMoney($yaoqingUserReceivabeOrderList);
       
        //当前用户自己回款的记录
        $data[]['uid']=$uid;
        $UserReceivabeOrderList = $this->getUserReceivableOrderList($data,$val);
        //计算当前用户自己的回款总额
        $UserReceivabe = $this->ReceivableOrderMoney($UserReceivabeOrderList);
        //$zonge = ($Userarr['zonger']+$YaoQingUserarr['zonger']);
        $zonge = ($Userarr['zonger']);
        //$nianhuan = ($Userarr['nianhuan']+$YaoQingUserarr['nianhuan']);
        $nianhuan = ($Userarr['nianhuan']);
        //$huikuan = ($yaoqingUserReceivabe+$UserReceivabe);
        $huikuan = ($UserReceivabe);
        //获取邀请的客户
        
        $yaoqingrenNumber = 0;
        $yaoqingrenNumber = count($yaoqingUserUidlist);
        return array(
            'yaoqingrencount'=>$yaoqingrenNumber,
            'zonge'=>number_format($zonge,2,".",""),
            'nianhuan'=>number_format($nianhuan,2,".",""),
            'huikuan'=>number_format($huikuan,2,".","")
        );
    }
    /************************************************************
     * @copyright(c): 2017年3月21日
     * @Author:  yuwen
     * @Create Time: 下午4:52:00
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @回款金额计算
     *************************************************************/
    public function ReceivableOrderMoney($data){
        if(empty($data)){
            return 0;
        }
        $benxi = 0;
        foreach ($data as $key=>$val){
            if(!empty($val['benxi'])){
                $benxi+=$val['benxi'];
            }
        }
        return $benxi;
    }
    /************************************************************
     * @copyright(c): 2017年3月21日
     * @Author:  yuwen
     * @Create Time: 下午4:39:12
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @计算投资总额
     * @投资总额
     * @投资年化
     *************************************************************/
    public function calculateData($data){
        $tmparr = array('zonger'=>null,'nianhuan'=>null);
        if(empty($data)){
            return $tmparr;
        }
        $order_money  =0;
        $ninhuajiaoyi =0;
        foreach ($data as $Key=>$val){
            if(!empty($val['order_money']) && $val['order_money']>0){
                if($val['expires_type']==1){
                    $ninhuajiaoyi+=$val['order_money']/360*$val['expires'];  //按天计算年化交易额
                }else{
                    $ninhuajiaoyi+=$val['order_money']/12*$val['expires'];    //按月计算年化交易额
                }
                $order_money+=$val['order_money'];
            }
        }
        return array('zonger'=>number_format($order_money,2,".",""),'nianhuan'=>number_format($ninhuajiaoyi,2,".",""));
    }
    /************************************************************
     * @copyright(c): 2017年3月21日
     * @Author:  yuwen
     * @Create Time: 下午4:14:52
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @根据条件查询用户回款记录
     *************************************************************/
    public function getUserReceivableOrderList($array,$val){
        $where = " and refund_time>='".strtotime($val['start'])."' and refund_time<='".strtotime($val['end'])."'";
        //$where=null;        //临时使用后期删掉
        $dealRecordDao = InitPHP::getDao("dealRecord");
        $tmparr = array();
        foreach ($array as $key=>$val){
            $uid = intval($val['uid']);//邀请的用户uid
            $arr = $dealRecordDao->getUserReceivableOrderList($uid,$where);
            $tmparr=array_merge($tmparr,$arr);
        }
        return $tmparr;
    }
    /************************************************************
     * @copyright(c): 2017年3月21日
     * @Author:  yuwen
     * @Create Time: 下午4:00:02
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @返回邀请的客户订单列表
     *************************************************************/
    public function getYaoQingUserOrderList($uidArr,$val){
        $myResultsDao = InitPHP::getDao("myResults");
        //$where = ' and a.create_time>='.strtotime($val['start']).' and a.create_time<='.strtotime($val['end']);
        $where=null;//临时赋值后面删除
        $tmparr = array();
        //循环用户获取用户订单列表
        foreach ($uidArr as $key=>$val){
            $uid = intval($val['uid']);//邀请的用户uid
            $yaoqingUserOrderList = $myResultsDao->getUserOrderList($uid,$where);
            $tmparr=array_merge($tmparr,$yaoqingUserOrderList);
            //找到用户投资的订单
        }
        return $tmparr;
    }
    /************************************************************
     * @copyright(c): 2017年3月21日
     * @Author:  yuwen
     * @Create Time: 下午3:34:46
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @获取在时间段内邀请的客户列表
     *************************************************************/
    public function getYaoQingRenList($uid,$val){
        $useryaoqingmalistDao = InitPHP::getDao("user_yaoqingma_list");
        $yaoqingwhere = ' and add_date>='.strtotime($val['start']).' and add_date<='.strtotime($val['end']);
        //$yaoqingwhere=null;//这里测试先写死后面删掉就好了
        $yaoqinglistUid = $useryaoqingmalistDao->getUidlist($uid,$yaoqingwhere);
        return $yaoqinglistUid;
    }
    /************************************************************
     * @copyright(c): 2017年2月8日
     * @Author:  yuwen
     * @Create Time: 下午2:01:18
     * @处理返回年份
     *************************************************************/
    public function returnnianfen($userinfo){
        $year_monthly = date("Y-m",$userinfo['regtime']);//得到用户注册时间
        //$year_monthly = '2016-10';//临时查询数据使用
        return $this->show12month($year_monthly);
    }
    /************************************************************
     * @copyright(c): 2017年1月23日
     * @Author:  yuwen
     * @Create Time: 上午10:14:27
     * @12月份显示
     *************************************************************/
    public function show12month($year_monthly){
        if(empty($year_monthly)){
            return array();
        }
        $time1 = strtotime($year_monthly); //自动为00:00:00 时分秒
        $time2 = strtotime(date("Y-m"));
        $monarr = array();
        $monarr[$year_monthly] = $this->getthemonth($year_monthly); // 当前月;
        while( ($time1 = strtotime('+1 month', $time1)) <= $time2){
            $monarr[date('Y-m',$time1)] = $this->getthemonth(date('Y-m',$time1)); //取得递增月;
        }
        return $monarr;
    }
    /************************************************************
     * @copyright(c): 2017年1月23日
     * @Author:  yuwen
     * @Create Time: 上午11:45:47
     * @ 计算当前年月的开始年月日和结束年月日
     *************************************************************/
    function getthemonth($date){
        $firstday = date('Y-m-01', strtotime($date));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        return array('start'=>$firstday.' 00:00:00','end'=>$lastday.' 23:59:59');
    }
}