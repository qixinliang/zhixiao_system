<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 职位业务层
 * @author aaron
 */
class roleService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->roleDao = InitPHP::getDao("role");//获取标的分类
        $this->adminService = InitPHP::getService("admin");//获取管理员Service
    }
    /**
     * 获取用户组列表
     */
    public function adminList()
    {
        return $this->roleDao->adminList();
    }
    /**
     * 根据id获取详情
     */
    public function info($id)
    {
        return $this->roleDao->adminGroupInfo($id);
    }
    /**
     * 添加
     */
    public function add_save($data)
    {
        $result=$this->data_save($data);
        return $this->roleDao->add_save($result);
    }
    public function data_save($data)
    {
        if(!empty($data['class_power'])){
            $data['class_power']=substr($data['class_power'],0,-1);
        }
        $model_power = null;
        if(is_array($data['model_power'])){
            foreach ($data['model_power'] as $value) {
                $model_power.=$value.',';
            }
            $data['model_power']=substr($model_power,0,-1);
        }else{
            $data['model_power']='';
        }
        return $data;
    }
    /**
     * 根据id获取组信息
     */
    public function edit($id)
    {
        $info = $this->roleDao->info($id);
        $datas['model_power'] = explode(',', $info['model_power']);
        $datas['info'] = $info;
        return $datas;
    }
    /**
     * 修改保存
     */
    public function edit_save($data)
    {
        $data=$this->data_save($data);
        return $this->roleDao->edit_save($data);
    }
    /**
     * 删除
     */
    public function del($id)
    {
        $info=$this->roleDao->info($id);
        if($info['keep']==1){
            return 2;
        }
        $user = $this->adminService->current_user();
        if($info['grade']<$user['grade']){
            return 3;
        }
        return  $this->roleDao->del($id);
    }
}
