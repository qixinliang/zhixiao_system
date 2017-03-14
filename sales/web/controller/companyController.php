<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/************************************************************
 * @copyright(c): 2017年2月27日
 * @Author:  yuwen
 * @Create Time: 下午5:23:16
 * @qq:32891873
 * @email:fuyuwen88@126.com
 * @公司列表管理
 *************************************************************/
class companyController extends baseController{
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
        $this->companyService = InitPHP::getService("company");       //获取Service
        $this->adminService = InitPHP::getService("admin");//获取Service
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
// 		$this->authService->checkauth("1017");     //权限检测目前这里暂时注释
        $list = $this->companyService->GetCompanyList();
        $this->view->assign('list', $list);
        $this->view->display("company/run"); //使用模板
    }
   /************************************************************
    * @copyright(c): 2017年2月27日
    * @Author:  yuwen
    * @Create Time: 下午6:53:25
    * @qq:32891873
    * @email:fuyuwen88@126.com
    * @添加显示页面
    *************************************************************/
    public function add(){
        $userinfo = $this->adminService->current_user();
        $this->view->assign('userinfo', $userinfo);
        $this->view->assign('action', 'AddSave');
        $this->view->display("company/addinfo"); //使用模板
    }
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午7:02:38
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @保存信息
     *************************************************************/
    public function AddSave(){
        if(empty($_POST['regtime'])){
            $_POST['regtime'] = time();
        }
        if(empty($_POST['regtime'])){
            $_POST['uptime'] = time();
        }
        $arr = $this->companyService->AddSave($_POST);
        if($arr){
            exit(json_encode(array('status' => 1, 'message' => '公司名称添加成功!')));
        }else{
            exit(json_encode(array('status' => 0, 'message' => '公司名称添加失败!')));
        }
    }
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午7:37:39
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @修改显示
     *************************************************************/
    public function edit(){
        $id = $this->controller->get_gp('id');
        $data=$this->companyService->CompanyInfoOne($id);
        if(empty($data)){
            exit(json_encode(array('status' => 0, 'message' => '操作错误！')));
        }
        $userinfo = $this->adminService->current_user();
        $this->view->assign('userinfo', $userinfo);
        $this->view->assign('data', $data);
        $this->view->assign('action', 'EditSave');
        $this->view->display("company/editinfo");
    }
    
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午7:53:24
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @修改保存
     *************************************************************/
    public function EditSave(){
        $arr = $this->companyService->EditSave($_POST);
        if($arr){
            exit(json_encode(array('status' => 1, 'message' => '公司信息修改成功!')));
        }
    }
 /************************************************************
  * @copyright(c): 2017年2月27日
  * @Author:  yuwen
  * @Create Time: 下午7:55:53
  * @qq:32891873
  * @email:fuyuwen88@126.com
  * @删除成功
  *************************************************************/
    public function Del(){
        $id = $this->controller->get_gp('id');
        $arr = $this->companyService->Del($id);
        if($arr==1){
            exit(json_encode(array('status' => 1, 'message' => '公司信息删除成功!')));
        }
        if($arr==2){
            exit(json_encode(array('status' => 2, 'message' => '无法删除!')));
        }
        if($arr==3){
            exit(json_encode(array('status' => 3, 'message' => '越权操作!')));
        }
    }
}
