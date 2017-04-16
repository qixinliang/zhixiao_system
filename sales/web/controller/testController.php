<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/************************************************************
 * @copyright(c): 2017年2月27日
 * @Author:  yuwen
 * @Create Time: 下午5:23:16
 * @qq:32891873
 * @email:fuyuwen88@126.com
 * @测试
 *************************************************************/
class testController extends baseController{
    public $initphp_list = array('add','AddSave','edit','EditSave','Del'); //Action白名单

    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午6:05:29
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @构造函数自动加载
     *************************************************************/
    public function __construct(){
        parent::__construct();
    }
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午6:05:46
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @显示默认列表页面
     *************************************************************/
    public function run(){
        header("Content-Type: application/vnd.ms-execl");
        header("Content-Disposition: attachment; filename=投标明细".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //execl 头标题
        $data1=mb_convert_encoding('用户名',"GB2312","UTF-8");
		$data2=mb_convert_encoding('员工姓名',"GB2312","UTF-8");
        $data3=mb_convert_encoding('联系手机',"GB2312","UTF-8");
        $data4=mb_convert_encoding('所属部门',"GB2312","UTF-8");
        $data5=mb_convert_encoding('角色',"GB2312","UTF-8");
        $data6=mb_convert_encoding('直属管理人',"GB2312","UTF-8");
        $data7=mb_convert_encoding('创建时间',"GB2312","UTF-8");
        echo $data1."\t";
        echo $data2."\t";
        echo $data3."\t";
        echo $data4."\t";
        echo $data5."\t";
        echo $data6."\t";
		echo $data7."\t";
        echo "\t\n";
        
        $adminService = InitPHP::getService("admin");         //获取Service
        $list = $adminService->admin_list();
        

        foreach($list as $key=>$val)
        {
            $arr1=mb_convert_encoding($val['user'],"GB2312","UTF-8");
            echo $arr1."\t";
            $arr2=mb_convert_encoding($val['UsrName'],"GB2312","UTF-8");
            echo $arr2."\t";
            $arr3=mb_convert_encoding($val['phone'],"GB2312","UTF-8");
            echo $arr3."\t";

            $arr4=mb_convert_encoding($val['department_name'],"GB2312","UTF-8");
            echo $arr4."\t";
            $arr5=mb_convert_encoding($val['gname'],"GB2312","UTF-8");
            echo $arr5."\t";
            $arr6=mb_convert_encoding($val['privilege'],"GB2312","UTF-8");
            echo $arr6."\t";
            $arr7=mb_convert_encoding($val['regtime'],"GB2312","UTF-8");
            echo $arr7."\t";
            echo "\t\n";
        }
    }
  
}
