<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/************************************************************
 * @copyright(c): 2017年2月27日
 * @Author:  yuwen
 * @Create Time: 下午5:33:12
 * @qq:32891873
 * @email:fuyuwen88@126.com
 * @公司列表显示
 *************************************************************/
class companyService extends Service{
    
    public function __construct(){
        parent::__construct();
        $this->company = InitPHP::getDao("company"); //获取标的分类
    }
    
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午5:37:39
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @获取公司列表
     *************************************************************/
    public function GetCompanyList(){
        return $this->company->GetCompanyList();
    }
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午5:38:19
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @根据用户id获取单条公司记录
     *************************************************************/
    public function CompanyInfoOne($id){
        return $this->company->CompanyInfoOne($id);
    }
    
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午5:40:31
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @添加公司信息
     *************************************************************/
    public function AddSave($data){
        return $this->company->AddSave($data);
    }
    
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午5:42:20
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * 修改保存
     *************************************************************/
    public function EditSave($data){
        return $this->company->EditSave($data);
    }
    
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午5:43:27
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @删除记录
     *************************************************************/
    public function Del($id){
        return  $this->company->Del($id);
    }
}