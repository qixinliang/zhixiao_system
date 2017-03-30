<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 创建excel表格
 * @author cxd
 */
class createExcelService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function run($data){
        header("Content-Type: application/vnd.ms-execl");
        header("Content-Disposition: attachment; filename=部门业绩明细".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        //execl 头标题
        $data1=mb_convert_encoding('ID',"GB2312","UTF-8");
        $data2=mb_convert_encoding('用户名',"GB2312","UTF-8");
        $data3=mb_convert_encoding('姓名',"GB2312","UTF-8");
        $data4=mb_convert_encoding('手机号',"GB2312","UTF-8");
        $data5=mb_convert_encoding('角色',"GB2312","UTF-8");
        $data6=mb_convert_encoding('级别',"GB2312","UTF-8");
        $data7=mb_convert_encoding('所属部门',"GB2312","UTF-8");
        $data8=mb_convert_encoding('入金规模',"GB2312","UTF-8");
        $data9=mb_convert_encoding('折标金额',"GB2312","UTF-8");
        $data10=mb_convert_encoding('回款',"GB2312","UTF-8");
        $data11=mb_convert_encoding('入职时间',"GB2312","UTF-8");
        $data12=mb_convert_encoding('客户数量',"GB2312","UTF-8");
        echo $data1."\t";
        echo $data2."\t";
        echo $data3."\t";
        echo $data4."\t";
        echo $data5."\t";
        echo $data6."\t";
        echo $data7."\t";
        echo $data8."\t";
        echo $data9."\t";
        echo $data10."\t";
        echo $data11."\t";
        echo $data12."\t";
        echo "\t\n";

        foreach($data as $key=>$val)
        {
            $arr1=mb_convert_encoding($val['id'],"GB2312","UTF-8");
            echo $arr1."\t";
            $arr2=mb_convert_encoding($val['user'],"GB2312","UTF-8");
            echo $arr2."\t";
            $arr3=mb_convert_encoding($val['UsrName'],"GB2312","UTF-8");
            echo $arr3."\t";
            $arr4=mb_convert_encoding($val['phone'],"GB2312","UTF-8");
            echo $arr4."\t";
            $arr5=mb_convert_encoding($val['name'],"GB2312","UTF-8");
            echo $arr5."\t";
            $arr6=mb_convert_encoding($val['level_id'],"GB2312","UTF-8");
            echo $arr6."\t";
            $arr7=mb_convert_encoding($val['department_name'],"GB2312","UTF-8");
            echo $arr7."\t";
            $arr8=mb_convert_encoding($val['zonge'],"GB2312","UTF-8");
            echo $arr8."\t";
            $arr9=mb_convert_encoding($val['nianhuan'],"GB2312","UTF-8");
            echo $arr9."\t";
            $arr10=mb_convert_encoding($val['huikuan'],"GB2312","UTF-8");
            echo $arr10."\t";
            $arr11=mb_convert_encoding($val['Inthetime'],"GB2312","UTF-8");
            echo $arr11."\t";
            $arr12=mb_convert_encoding($val['yaoqingrencount'],"GB2312","UTF-8");
            echo $arr12."\t";
            echo "\t\n";
        }
    }
    
}

