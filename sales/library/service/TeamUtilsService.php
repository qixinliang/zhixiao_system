<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/**
 * 公用方法类
 * @author aaron
 */
class TeamUtilsService extends Service{
    
    private $touzirenshuliang;     //投资人数量
    private $touzizongedu;         //投资总额度
    
   /*
    * 数组分页公共方法
    * $url 拼接的url地址
    * $count 总条数
    * $limit 每页显示条数
    * $array 分页的数组
    */
    public function cuttingDataPage($url,$limit,$array){
        if(empty($array)||!is_array($array)){
            return array();
        }
        $pages =isset($_GET['page'])?intval($_GET['page']):1;
        $start=($pages-1)*$limit;
       return array('list'=>array_slice($array,$start,$limit),'page'=>$pages);
    }
    /************************************************************
     * @copyright(c): 2017年1月9日
     * @Author:  yuwen
     * @Create Time: 上午10:51:39
     * @用户名截取显示
     *************************************************************/
    function cut_str($string, $sublen, $start = 0, $code = 'UTF-8'){
        if($code == 'UTF-8'){
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen));
            return join('', array_slice($t_string[0], $start, $sublen));
        }else{
            $start = $start*2;
            $sublen = $sublen*2;
            $strlen = strlen($string);
            $tmpstr = '';
            for($i=0; $i< $strlen; $i++){
                if($i>=$start && $i< ($start+$sublen)){
                    if(ord(substr($string, $i, 1))>129){
                        $tmpstr.= substr($string, $i, 2);
                    }else{
                        $tmpstr.= substr($string, $i, 1);
                    }
                }
                if(ord(substr($string, $i, 1))>129) $i++;
            }
            //if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
            return $tmpstr;
        }
    }
    
    /*
     * 根据权限显示或隐藏用户名和手机号码并排序数组
     * $data,           数据
     * $permissions_id  权限id
     */
    public function isShowInfo($data,$permissions_id) {
        $authService = InitPHP::getService("auth");//权限逻辑层引入
        $tmparr = array();
        if(empty($data)){
            return $tmparr;
        }
        foreach ($data as $key=>$val){
            if($authService->checkauthUser($permissions_id)==false){
                $val['username'] = $this->cut_str($val['username'], 1, 0).'**';
                $val['UsrName'] = $this->cut_str($val['UsrName'], 1, 0).'**';
                $val['tuijianrenName']=$this->cut_str($val['tuijianrenName'], 1, 0).'**';
                $val['phone']=substr_replace($val['phone'],'****',3,4);
            }
            $tmparr[$key]=$val;
        }
        rsort($tmparr);
        return $tmparr;
    }    
    /*
     * @合计订单总额数公共方法
     */
    public function aLlOrderCombined($data){
        $zjiaoyieheji           = 0;  //总交易额合计
        $znianhuajiaoyieheji    = 0;  //年化交易额合计
        $ztuijianjingtie        = 0;  //推荐津贴合计
        if(empty($data)){
            return array();
        }
        foreach ($data as $key=>$val){
            $ztuijianjingtie        += $val['tuijianjingtie'];          //总的推荐经贴
            $znianhuajiaoyieheji    += $val['touzinianhuaCout'];        //总的年化交易额
            $zjiaoyieheji           += $val['touzizongCout'];           //总交易额合计
        }
        return array(
            'ztuijianjingtie'=>number_format($ztuijianjingtie,2,".",""),
            'znianhuajiaoyieheji'=>number_format($znianhuajiaoyieheji,2,".",""),
            'zjiaoyieheji'=>number_format($zjiaoyieheji,2,".","")
        );
    }
    /************************************************************
     * @copyright(c): 2016年12月28日
     * @Author:  yuwen
     * @Create Time: 下午2:36:42
     * @计算处理递归处理后的总年化交易额总数
     *************************************************************/
    public function SumAnnualTrading($data){
        $duiguinianhuajiaoyie = 0; //递归处理完的年化交易额
        if(empty($data)||!is_array($data)){
            return $duiguinianhuajiaoyie;
        }
        foreach ($data as $key=>$val){
            $duiguinianhuajiaoyie+=$val['ZNHJYE'];
        }
        return $duiguinianhuajiaoyie;
    }
    /************************************************************
     * @copyright(c): 2016年12月29日
     * @Author:  yuwen
     * @Create Time: 下午9:45:22
     * @ 个人用佣金计算方法
     *************************************************************/
    public function nianHuaJiaoYiEJiSuan($data,$yongjinbili){
        $tmparray = array();
        if(empty($data) || !is_array($data)){
            return $tmparray;
        }
        foreach ($data as $key=>$val){
            if(!empty($val['order_money']) && $val['order_money']>0){
                if($val['expires_type']==1){
                    $val['NHJYE']=$val['order_money']/360*$val['expires'];  //按天计算年化交易额
                }else{
                    $val['NHJYE']=$val['order_money']/12*$val['expires'];    //按月计算年化交易额
                }
                $val['NHJYE']   = number_format($val['NHJYE'],2,".","");      //年化交易额
                $val['YONGJIN'] = number_format(($val['order_money']*$yongjinbili),2,".","");//佣金
                $val['yongjinbili'] =  ($yongjinbili*100);             //佣金比例类中定义
                $tmparray[$key]=$val;
            }
        }
        return $tmparray;
    }
    /************************************************************
     * @copyright(c): 2016年12月28日
     * @Author:  yuwen
     * @Create Time: 下午2:00:36
     * @计算页面显示的交易额合计，年化交易额合计，推荐经贴合计
     *************************************************************/
    public function combinedTurnoverAnAnnualByThePost($data){
        $touzijine              = 0;  //投资金额合计
        $nianhuajiaoyie         = 0;  //年化交易额合计
        $yongjin                = 0;  //佣金合计
        if(empty($data)){
            return array();
        }
        foreach ($data as $key=>$val){
            $touzijine          += $val['order_money'];        //投资金额合计
            $nianhuajiaoyie     += $val['NHJYE'];             //年化交易额合计
            $yongjin            += $val['YONGJIN'];           //佣金合计
        }
        return array(
            'touzijine'=>number_format($touzijine,2,".",""),
            'nianhuajiaoyie'=>number_format($nianhuajiaoyie,2,".",""),
            'yongjin'=>number_format($yongjin,2,".","")
        );
    }
    /************************************************************
     * @copyright(c): 2016年12月16日
     * @Author:  yuwen
     * @Create Time: 下午2:20:52
     * @数组数据按时间段筛选专用查询条件处理
     * @$data 二维数组源数据
     * @$username 姓名
     * @$phone 手机号码
     *************************************************************/
    public function arraysereachdate($data,$username,$phone){
        $tmp = array();
        if(!is_array($data)){
            return $tmp;
        }
        foreach ($data as $key => $value) {
            if(trim($value['UsrName'])==trim($username)||trim($value['phone'])==trim($phone)){
                $tmp[]=$value;
            }
        }
        return $tmp;
    }
    
    /************************************************************
     * @copyright(c): 2017年3月21日
     * @Author:  cxd
     * @Create Time: 下午3:30:52
     * @年化收益额计算
     * @$data 标的订单列表
     *************************************************************/
    public function yongJinJiSuan($data){
        $tmparray = array();
        if(empty($data)){
            return $tmparray;
        }
        foreach ($data as $key=>$val){
            if(!empty($val['order_money']) && $val['order_money']>0){
                if($val['expires_type']==1){
                    $val['nhsyl']=$val['order_money']/360*$val['expires'];  //按天计算年化收益额
                }else{
                    $val['nhsyl']=$val['order_money']/12*$val['expires'];    //按月计算年化收益额
                }
            }
            $tmparray[] = $val;
        }
        return $tmparray;
    }
}
