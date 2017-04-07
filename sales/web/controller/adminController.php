<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');

/**
 * 后台管理员控制器
 * @author aaron
 */
class adminController extends baseController
{
    public $initphp_list = array('add','add_save','edit','edit_save','del'); //Action白名单

    public function __construct()
    {
        parent::__construct();
        $this->adminService = InitPHP::getService("admin");//获取Service
        $this->roleService = InitPHP::getService("role");//获取Service
		$this->authService = InitPHP::getService("auth");//获取权限Service
    }
    /**
     * 默认Action
     * @author aaron
     */
    public function run()
    {
        $this->authService->checkauth("1013");
        $departmentService = InitPHP::getService("department"); //上级列表
        
        $part_id = $this->controller->get_gp('part_id') ? $this->controller->get_gp('part_id') : '';    //获取角色id
        $department_id = $this->controller->get_gp('department_id') ? $this->controller->get_gp('department_id') : '';//获取部门id
        $unmae = $this->controller->get_gp('uname') ? $this->controller->get_gp('uname') : '';//获取用户姓名
        $phone = $this->controller->get_gp('phone') ? $this->controller->get_gp('phone') : '';//获取用户手机
        
        //根据检索条件，拼接sql语句where条件
        $where = $this->adminService->getWhere($part_id,$department_id,$unmae,$phone);
        $list = $this->adminService->admin_list($where); //查询列表数据
        $adminList = $this->roleService->adminList(); //获取角色列表
        $list2 = $departmentService->getDepartmentList2();//获取所属部门列表
        $tree2 = $this->_generateTree2($list2);
        $html = $this->_exportTree($tree2,$department_id);
        $list = $this->getToObtainTheSuperior($list);//查找直属负责人
        //映射检索条件
        $this->view->assign('part', $part_id);
        $this->view->assign('uname', $unmae);
        $this->view->assign('phone', $phone);
        $this->view->assign('html', $html);
        $this->view->assign('list', $list);
        $this->view->assign('adminList',$adminList);
        $this->view->display("admin/run");
    }
    /************************************************************
     * @copyright(c): 2017年3月28日
     * @Author:  yuwen
     * @Create Time: 下午5:23:44
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @获取上级部门
     *************************************************************/
    public function getToObtainTheSuperior($list){
        if(empty($list)){
            return array();
        }
        foreach ($list as $key=>$val){
            $val['superior'] =null;
            $val['superior_position'] =null;
            $val['superior_department_name'] =null;
            //获取上级部门
            $res = $this->adminService->getParentNodeById($val['department_id']);
            if(intval($res['department_id'])>0||intval($val['gid'])==4||intval($val['gid'])==2){
                //获取上级部门内有多少用户
                //10销售4副总裁5城市总经理
                if($val['gid']==10||$val['gid']==4||$val['gid']==2){
                    $userinfo = $this->adminService->getdepartmentTheUser(intval($val['department_id']));
                }else{
                    $userinfo = $this->adminService->getdepartmentTheUser(intval($res['department_id']));
                    if($val['gid']==5){
                        rsort($userinfo);
                    }
                }
                if (!empty($userinfo)){
                    foreach ($userinfo as $k=>$v){
                        if($v['privilege']>$val['privilege']){
                            $val['superior'] =$v['UsrName'];
                            $val['superior_position'] =$v['name'];
                            $val['superior_department_name'] =$v['department_name'];
                            $list[$key]=$val;
                            break;
                        }
                    }
                }
            }
        }
        return $list;
    }
    /**
     * 添加
     * @author aaron
     */
    public function add()
    {
        $this->authService->checkauth("1014");
        $departmentService = InitPHP::getService("department");//上级列表
        $user_group = $this->roleService->adminList();
        $list2 = $departmentService->getDepartmentList2();
        $tree2 = $this->_generateTree2($list2);
        $html = $this->_exportTree($tree2);
        $this->view->assign('html', $html);
        $this->view->assign('action_name', '添加');
        $this->view->assign('action', 'add');
        $this->view->assign('user_group', $user_group);
        $this->view->display("admin/addinfo");
    }
    /**
     * 添加保存
     * @author aaron
     */
    public function add_save()
    {	
        if($this->authService->checkauthUser("1015")==false){
            exit(json_encode(array('status' => 0, 'message' => '您没有权限操作')));
        }
		$token = $this->controller->check_token(); 
		if(!$token) exit;//如果token不存在则退出
		
		//获取当前用户的session_id用于写入推荐人
		$this->loginService = InitPHP::getService("login");
        $admin = $this->loginService->isLogin();
		
		//参数封装到data
		$data['gid'] = $this->controller->get_gp('gid');
		$data['user'] = $this->controller->get_gp('user');
		$data['UsrName'] = $this->controller->get_gp('UsrName');
		//$data['IdNo'] = $this->controller->get_gp('IdNo');
		$data['phone'] = $this->controller->get_gp('phone');
		$data['password'] = $this->controller->get_gp('password');
		$data['password2'] = $this->controller->get_gp('password2');
		$data['Inthetime'] = $this->controller->get_gp('Inthetime');
		$data['department_id'] = $this->controller->get_gp('department_id');
		$data['level_id'] = $this->controller->get_gp('level_id');
		$data['gender'] = $this->controller->get_gp('gender');
		
		
		$data['status'] = $this->controller->get_gp('status');
		
        $arr = $this->adminService->add_save($data,$admin['id']);
        if($arr == 1){
            exit(json_encode(array('status' => 1, 'message' => '两次密码输入不同！')));
        }else if($arr == 2){
			exit(json_encode(array('status' => 2, 'message' => '角色账号在业务系统中已存在，请更换输入账号！')));
		}else if($arr == 3){
			exit(json_encode(array('status' => 3, 'message' => '角色账号添加成功！')));
		}else if($arr == 4){
			exit(json_encode(array('status' => 4, 'message' => '投资账号已存在，请输入正确的身份证号及手机号！')));
		}else if($arr == 7){
			exit(json_encode(array('status' => 7, 'message' => '身份证在投资系统中已存在！')));
		}else if($arr == 123){
			exit(json_encode(array('status' => 123, 'message' => '用户名必须要6-16位字母、数字和下划线！')));
		}else if($arr == 234){
			exit(json_encode(array('status' => 234, 'message' => '用户名只能包含数字、字母、下划线，不能使用特殊字符！')));
		}else if($arr == 345){
			exit(json_encode(array('status' => 345, 'message' => '用户名不能为手机号！')));
		}else if($arr == 456){
			exit(json_encode(array('status' => 456, 'message' => '用户名在投资系统中已存在,请输入正确的身份证号及手机号！')));
		}else if($arr == 567){
			exit(json_encode(array('status' => 567, 'message' => '业务系统手机号已存在！')));
		}else if($arr == 678){
			exit(json_encode(array('status' => 678, 'message' => '角色账号添加成功！')));
		}
    }
    /**
     * 修改
     * @author aaron
     */
    public function edit()
    {
        $this->authService->checkauth("1016");
        $id = $this->controller->get_gp('id');
        $arr=$this->adminService->edit($id);
        $departmentService = InitPHP::getService("department");//上级列表
        $list2 = $departmentService->getDepartmentList2();
        $tree2 = $this->_generateTree2($list2);
        $department_id = $arr['info']['department_id'];
        $html = $this->_exportTree($tree2,$department_id);
        $this->view->assign('html', $html);
        $this->view->assign('user_group', $arr['user_group']);
        $this->view->assign('info', $arr['info']);
        $this->view->assign('action_name', '修改');
        $this->view->assign('action', 'edit');
        $this->view->display("admin/editinfo");
    }
    /**
     * 修改保存
     * @author aaron
     */
    public function edit_save()
    {
        if($this->authService->checkauthUser("1017")==false){
            exit(json_encode(array('status' => 0, 'message' => '您没有权限操作')));
        }
		$token = $this->controller->check_token(); 
		if(!$token) exit;//如果token不存在则退出
		
		$data['id'] = $this->controller->get_gp('id');//自然ID
		$data['gid'] = $this->controller->get_gp('gid');//角色ID
        $data['department_id'] = $this->controller->get_gp('department_id');//部门ID
		$data['UsrName'] = $this->controller->get_gp('UsrName');//用户名
		//$data['phone'] = $this->controller->get_gp('phone');//手机号
		$data['password'] = $this->controller->get_gp('password');//密码
		$data['password2'] = $this->controller->get_gp('password2');//确认密码
		$data['level_id'] = $this->controller->get_gp('level_id');
		$data['gender'] = $this->controller->get_gp('gender');//性别
		$data['departure'] = $this->controller->get_gp('departure');//在职状态
		$data['status'] = $this->controller->get_gp('status');//状态
		$data['Inthetime'] = $this->controller->get_gp('Inthetime');
        if($data['departure'] == 0){ //如果离职触发客户池功能
            //FIXME,调用客户分配服务层中的方法，把数据写入公共池中。
        }
        $arr = $this->adminService->edit_save($data);
        if($arr==5)
        {
            exit(json_encode(array('status' => 5, 'message' => '未填写确认密码')));
        }else if($arr==6)
        {
            exit(json_encode(array('status' => 6, 'message' => '两次密码输入不同')));
        }else if($arr==7)
        {
            exit(json_encode(array('status' => 7, 'message' => '帐号已存在')));
        }else if($arr==8)
        {
            exit(json_encode(array('status' => 8, 'message' => '修改成功!')));
        }
    }
    /**
     * 删除
     * @author aaron
     */
    public function del()
    {
        if($this->authService->checkauthUser("1018")==false){
            exit(json_encode(array('status' => 0, 'message' => '您没有权限操作')));
        }
        $id = $this->controller->get_gp('id');
        $arr= $this->adminService->del($id);
        if($arr==9)
        {
            exit(json_encode(array('status' => 9, 'message' => '内置管理员无法删除!')));
        }
        if($arr==10)
        {
            exit(json_encode(array('status' => 10, 'message' => '删除成功!')));
        }
    }
    private function _generateTree2($items){
        foreach($items as $item)
            $items[$item['p_dpt_id']]['son'][$item['department_id']] = &$items[$item['department_id']];
        return isset($items[0]['son']) ? $items[0]['son'] : array();
    }
    
    private function _exportTree($tree,$department_id=0,$deep = 0){
        static $html = '';
        foreach ($tree as $k => $v) {
            $tmpName = sprintf("%s%s", str_repeat('——', $deep), $v['department_name']);
            $html .= '<option value='.$k;
            if($v['department_id']==$department_id){
                $html.=' selected="selected" ';
            }
            $html .= '>';
            $html .= $tmpName . '</option>';
            
            if (isset($v['son']) && !empty($v['son'])) {
                $this->_exportTree($v['son'],$department_id, $deep + 1);
            }
        }
        return $html;
    }
}
