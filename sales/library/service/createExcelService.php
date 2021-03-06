<?php
if (! defined('IS_INITPHP'))
    exit('Access Denied!');

/**
 * 创建execl表格
 * @author cxd
 */
class createExcelService extends Service
{

    public function __construct()
    {
        parent::__construct();
    }

    public function run($data)
    {
        header("Content-Type: application/vnd.ms-execl");
        header("Content-Disposition: attachment; filename=部门业绩明细" . date('Y-m-d') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // execl头标题1
        $data1 = mb_convert_encoding('ID', "utf-8", "UTF-8");
        $data2 = mb_convert_encoding('用户名', "utf-8", "UTF-8");
        $data3 = mb_convert_encoding('姓名', "utf-8", "UTF-8");
        $data4 = mb_convert_encoding('手机号', "utf-8", "UTF-8");
        $data5 = mb_convert_encoding('角色', "utf-8", "UTF-8");
        $data6 = mb_convert_encoding('级别', "utf-8", "UTF-8");
        $data7 = mb_convert_encoding('所属部门', "utf-8", "UTF-8");
        $data8 = mb_convert_encoding('入金规模', "utf-8", "UTF-8");
        $data9 = mb_convert_encoding('折标金额', "utf-8", "UTF-8");
        $data10 = mb_convert_encoding('回款', "utf-8", "UTF-8");
        $data11 = mb_convert_encoding('入职时间', "utf-8", "UTF-8");
        $data12 = mb_convert_encoding('客户数量', "utf-8", "UTF-8");
        echo $data1 . "\t";
        echo $data2 . "\t";
        echo $data3 . "\t";
        echo $data4 . "\t";
        echo $data5 . "\t";
        echo $data6 . "\t";
        echo $data7 . "\t";
        echo $data8 . "\t";
        echo $data9 . "\t";
        echo $data10 . "\t";
        echo $data11 . "\t";
        echo $data12 . "\t";
        echo "\t\n";
        
        foreach ($data as $key => $val) {
            $arr1 = mb_convert_encoding($val['id'], "utf-8", "UTF-8");
            echo $arr1 . "\t";
            $arr2 = mb_convert_encoding($val['user'], "utf-8", "UTF-8");
            echo $arr2 . "\t";
            $arr3 = mb_convert_encoding($val['UsrName'], "utf-8", "UTF-8");
            echo $arr3 . "\t";
            $arr4 = mb_convert_encoding($val['phone'], "utf-8", "UTF-8");
            echo $arr4 . "\t";
            $arr5 = mb_convert_encoding($val['name'], "utf-8", "UTF-8");
            echo $arr5 . "\t";
            $arr6 = mb_convert_encoding($val['level_id'], "utf-8", "UTF-8");
            echo $arr6 . "\t";
            $arr7 = mb_convert_encoding($val['department_name'], "utf-8", "UTF-8");
            echo $arr7 . "\t";
            $arr8 = mb_convert_encoding($val['zonge'], "utf-8", "UTF-8");
            echo $arr8 . "\t";
            $arr9 = mb_convert_encoding($val['nianhuan'], "utf-8", "UTF-8");
            echo $arr9 . "\t";
            $arr10 = mb_convert_encoding($val['huikuan'], "utf-8", "UTF-8");
            echo $arr10 . "\t";
            $arr11 = mb_convert_encoding($val['Inthetime'], "utf-8", "UTF-8");
            echo $arr11 . "\t";
            $arr12 = mb_convert_encoding($val['yaoqingrencount'], "utf-8", "UTF-8");
            echo $arr12 . "\t";
            echo "\t\n";
        }
    }

	//部门业绩统计表格导出
    public function run2($data)
    {
        header("Content-Type: application/vnd.ms-execl");
        header("Content-Disposition: attachment; filename=部门业绩统计" . date('Y-m-d') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // execl头标题1
        $data1 = mb_convert_encoding('大区', "utf-8", "UTF-8");
        $data2 = mb_convert_encoding('分公司', "utf-8", "UTF-8");
        $data3 = mb_convert_encoding('部门', "utf-8", "UTF-8");
        $data4 = mb_convert_encoding('团队', "utf-8", "UTF-8");
        $data5 = mb_convert_encoding('员工姓名', "utf-8", "UTF-8");
        $data6 = mb_convert_encoding('职级', "utf-8", "UTF-8");
        $data7 = mb_convert_encoding('入职时间', "utf-8", "UTF-8");
        $data8 = mb_convert_encoding('入金规模', "utf-8", "UTF-8");
        $data9 = mb_convert_encoding('折标金额', "utf-8", "UTF-8");
        $data10 = mb_convert_encoding('回款', "utf-8", "UTF-8");
        echo $data1 . "\t";
        echo $data2 . "\t";
        echo $data3 . "\t";
        echo $data4 . "\t";
        echo $data5 . "\t";
        echo $data6 . "\t";
        echo $data7 . "\t";
        echo $data8 . "\t";
        echo $data9 . "\t";
        echo $data10 . "\t";
        echo "\t\n";
        
        foreach ($data as $key => $val) {
            $arr1 = mb_convert_encoding($val['info'][1], "utf-8", "UTF-8");
            echo $arr1 . "\t";
            $arr2 = mb_convert_encoding($val['info'][2], "utf-8", "UTF-8");
            echo $arr2 . "\t";
            $arr3 = mb_convert_encoding($val['info'][3], "utf-8", "UTF-8");
            echo $arr3 . "\t";
            $arr4 = mb_convert_encoding($val['info'][4], "utf-8", "UTF-8");
            echo $arr4 . "\t";
            $arr5 = mb_convert_encoding($val['UsrName'], "utf-8", "UTF-8");
            echo $arr5 . "\t";
            $arr6 = mb_convert_encoding($val['name'], "utf-8", "UTF-8");
            echo $arr6 . "\t";
            $arr7 = mb_convert_encoding($val['Inthetime'], "utf-8", "UTF-8");
            echo $arr7 . "\t";
            $arr8 = mb_convert_encoding($val['zonge'], "utf-8", "UTF-8");
            echo $arr8 . "\t";
            $arr9 = mb_convert_encoding($val['nianhuan'], "utf-8", "UTF-8");
            echo $arr9 . "\t";
            $arr10 = mb_convert_encoding($val['huikuan'], "utf-8", "UTF-8");
            echo $arr10 . "\t";
            echo "\t\n";
        }
    }

	//我的客户表格导出
    public function run3($data)
    {
        header("Content-Type: application/vnd.ms-execl");
        header("Content-Disposition: attachment; filename=我的客户" . date('Y-m-d') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // execl头标题1
        $data1 = mb_convert_encoding('项目名称', "utf-8", "UTF-8");
        $data2 = mb_convert_encoding('客户姓名', "utf-8", "UTF-8");
        $data3 = mb_convert_encoding('手机号', "utf-8", "UTF-8");
        $data4 = mb_convert_encoding('投资日期', "utf-8", "UTF-8");
        $data5 = mb_convert_encoding('成立日期', "utf-8", "UTF-8");
        $data6 = mb_convert_encoding('到期日期', "utf-8", "UTF-8");
        $data7 = mb_convert_encoding('投资金额（元）', "utf-8", "UTF-8");
        $data8 = mb_convert_encoding('投资利率', "utf-8", "UTF-8");
        $data9 = mb_convert_encoding('折标金额（元）', "utf-8", "UTF-8");
        $data10 = mb_convert_encoding('代金/加息券', "utf-8", "UTF-8");
        $data11 = mb_convert_encoding('当前业务员', "utf-8", "UTF-8");
        echo $data1 . "\t";
        echo $data2 . "\t";
        echo $data3 . "\t";
        echo $data4 . "\t";
        echo $data5 . "\t";
        echo $data6 . "\t";
        echo $data7 . "\t";
        echo $data8 . "\t";
        echo $data9 . "\t";
        echo $data10 . "\t";
        echo $data11 . "\t";
        echo "\t\n";
        
        foreach ($data as $key => $val) {
            $arr1 = mb_convert_encoding($val['title'], "utf-8", "UTF-8");
            echo $arr1 . "\t";
            $arr2 = mb_convert_encoding($val['UsrName'], "utf-8", "UTF-8");
            echo $arr2 . "\t";
            $arr3 = mb_convert_encoding($val['phone'], "utf-8", "UTF-8");
            echo $arr3 . "\t";
            $arr4 = mb_convert_encoding(date('Y-m-d',$val['order_time']), "utf-8", "UTF-8");
            echo $arr4 . "\t";
            $arr5 = mb_convert_encoding(date('Y-m-d',$val['start_date']), "utf-8", "UTF-8");
            echo $arr5 . "\t";
            $arr6 = mb_convert_encoding(date('Y-m-d',$val['deal_end_date']),"utf-8", "UTF-8");
            echo $arr6 . "\t";
            $arr7 = mb_convert_encoding($val['order_money'], "utf-8", "UTF-8");
            echo $arr7 . "\t";
            $arr8 = mb_convert_encoding($val['syl'], "utf-8", "UTF-8");
            echo $arr8 . "\t";
            $arr9 = mb_convert_encoding($val['nhsyl'], "utf-8", "UTF-8");
            echo $arr9 . "\t";
            $arr10 = mb_convert_encoding($val['JiaXi'], "utf-8", "UTF-8");
            echo $arr10 . "\t";
            $arr11 = mb_convert_encoding($val['salesman'], "utf-8", "UTF-8");
            echo $arr11 . "\t";
            echo "\t\n";
        }
    }

	//我的客户表格导出
    public function run4($data)
    {
        header("Content-Type: application/vnd.ms-execl");
        header("Content-Disposition: attachment; filename=我的业绩" . date('Y-m-d') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // execl头标题1
        $data1 = mb_convert_encoding('月份', "utf-8", "UTF-8");
        $data2 = mb_convert_encoding('入金规模', "utf-8", "UTF-8");
        $data3 = mb_convert_encoding('折标金额', "utf-8", "UTF-8");
        $data4 = mb_convert_encoding('回款金额', "utf-8", "UTF-8");
        $data5 = mb_convert_encoding('佣金收入', "utf-8", "UTF-8");
        $data6 = mb_convert_encoding('新增客户数', "utf-8", "UTF-8");
        echo $data1 . "\t";
        echo $data2 . "\t";
        echo $data3 . "\t";
        echo $data4 . "\t";
        echo $data5 . "\t";
        echo $data6 . "\t";
        echo "\t\n";
        
        foreach ($data as $key => $val) {
            $arr1 = mb_convert_encoding($key, "utf-8", "UTF-8");
            echo $arr1 . "\t";
            $arr2 = mb_convert_encoding($val['zonge'], "utf-8", "UTF-8");
            echo $arr2 . "\t";
            $arr3 = mb_convert_encoding($val['nianhuan'], "utf-8", "UTF-8");
            echo $arr3 . "\t";
            $arr4 = mb_convert_encoding($val['huikuan'], "utf-8", "UTF-8");
            echo $arr4 . "\t";
            $arr5 = mb_convert_encoding('',"utf-8", "UTF-8");
            echo $arr5 . "\t";
            $arr6 = mb_convert_encoding($val['yaoqingrencount'], "utf-8", "UTF-8");
            echo $arr6 . "\t";
            echo "\t\n";
        }
    }
}
