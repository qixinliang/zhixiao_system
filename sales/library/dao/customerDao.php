<?php
//客户管理
if (!defined('IS_INITPHP')) exit('Access Denied!');

class customerDao extends Dao{
	public $tableName = 'zx_customer_pool';
	public function __construct(){
		parent::__construct();
	}

	public function getCustomers($where = ''){
		$sql = sprintf("SELECT * FROM %s %s",$this->tableName,$where);
		$ret = $this->dao->db->get_all_sql($sql);
		return $ret;

		$ret = array(
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
			array(
				'customer_pool_id' 			=> 1,
				'investor_id' 				=> 20,
				'investor_login_name' 		=> 'abcdefg',
				'investor_real_name' 		=> 'abcdefg',
				'investor_cellphone' 		=> '13800138000',
				'inviter_id' 				=> '1',
				'inviter_name' 				=> 'zhangsan',
				'inviter_department_id' 	=> '承德一区-滦平一部',
				'inviter_role_id' 			=>  '业务员',
				'invest_status' 			=> 0,
				'inviter_off_time' 			=> time(),
				'create_time' 				=> time(),
				'update_time' 				=> time(),
			),
		);
		return $ret;
	}
	
	public function add2($adminId){
		//adminDao...
		$adminDao = InitPHP::getDao('admin');
		//找到销售人员在cp_user表的ID字段
		$cpUserId = $adminDao->GetToZiXiTongUserId($adminId); 
		$cpUserId = 3;
	
		//根据该销售人员获取一下他的(客户/投资人)信息
		$yaoqingDao = InitPHP::getDao('user_yaoqingma_listDao');
		$investorList = $yaoqingDao->getInvestorList($cpUserId);
		if(!isset($investorList) || empty($investorList)){
			return -1;
		}
		$fData = array();
		foreach($inverstorList as $k => $v){
			$data = array();
			$infoEx = $adminDao->adminInfoEx($adminId);
			if(!isset($infoEx) || empty($infoEx)){
				continue;
			}
			$departmentId = $infoEx['department_id'];
			$roleId = $infoEx['gid'];
			$data['investor_id'] = $v['uid'];
			$data['investor_login_name'] = $v['username'];
			$data['investor_real_name'] = $v['UsrName'];
			$data['investor_cellphone'] = $v['phone'];
			$data['inviter_id'] = $cpUserId;
			$data['inviter_name'] = $infoEx['UsrName']; 
			$data['inviter_department_id'] = $infoEx['department_id'];
			$data['inviter_role_id'] = $infoEx['gid'];
			$data['inviter_off_time'] = time();
			$data['invest_status'] = 0;
			$data['create_time'] = time();
			$data['update_time'] = time();
			$this->addSave($data);
			$fData[] = $data;
		}
		return $fData;
	}
	
    public function addSave($data){
        return $this->dao->db->insert($data, $this->tableName);
    }

	public function add($adminId){
	    $adminDao 		= InitPHP::getDao('admin');
	    $yaoqingDao 	= InitPHP::getDao('user_yaoqingma_list');
	    $id 			= $adminDao->GetToZiXiTongUserId($adminId);
		if(!isset($id) || empty($id)){
			return -1;
		}
		//得到当前登录销售系统的线上百合贷的UID
	    $uid 			= intval($id['id']);
		
		//FIXME 测试写死
		$uid = 3;
		//获取邀请的客户/投资人的UID list
	    $investorUids 	= $yaoqingDao->getUidlist($uid);
		if(!isset($investorUids) || empty($investorUids)){
			//未找到客户或者投资人
			return -1;
		}
	    $investInfo		= $this->getInvestInfo($investorUids);
		return $investInfo;
	}
		
	//客户分配原型中对应的投资人/邀请人信息汇总
	public function getInvestInfo($array){
	    if(!isset($array) || empty($array)){
	        return -1;
	    }

	    $adminDao = InitPHP::getDao('admin');
	    $tmparray = array();
	    foreach ($array as $key => $val){
	        $arr = $this->getUserinfo(intval($val['uid']));
	        if(!empty($arr)){
	            $xiaoshouUid = $adminDao->GetToZiXiTongAdminId(intval($arr['friends']));
	            $xiaoshouInfo = $this->getYaoQingRenUserInfo($xiaoshouUid);
				$arr['inviter_id'] = $xiaoshouUid;
	            $arr['inviter_name']=$xiaoshouInfo['xiaoshouloginname'];
	            $arr['inviter_department_id']=$xiaoshouInfo['department_id'];
	            $arr['inviter_role_id'] = 0;
	            $arr['inviter_off_time'] = time();
	            //$arr['bumenname']=$xiaoshouInfo['bumenname'];
	            //$arr['department_name']=$xiaoshouInfo['department_name'];
	            $xiaoshounameinfo = $this->getXiaoShouName(intval($arr['friends']));
	            //$arr['yaoqingrenloginname']=$xiaoshounameinfo['baihedailoginname'];
	            //$arr['YaoqingrenUsrName']=$xiaoshounameinfo['UsrName'];
	            $istrue = $this->IsUserWhethertoinvest(intval($arr['investor_id']));
	            //$istrue = $this->IsUserWhethertoinvest(intval($arr['id']));
	            if($istrue == true){
	                $arr['invest_status'] = 1;
	            }else{
	                $arr['invest_status'] = 0;
	            }
				$arr['create_time'] = time();
				$arr['update_time'] = time();
				
				unset($arr['friends']);
				var_dump($arr);

				//调用addSave方法写入数据库.
				$this->addSave($arr);

	            $tmparray=array_merge($tmparray,array($arr));
	        }
	    }
	   return $tmparray;
	}
	/************************************************************
	 * @copyright(c): 2017年3月22日
	 * @Author:  yuwen
	 * @Create Time: 下午4:08:57
	 * @qq:32891873
	 * @email:fuyuwen88@126.com
	 * @查询邀请人姓名，部门信息
	 * @传入当前用户UID查询出邀请的人姓名，所属部门，角色
	 *************************************************************/
	public function getYaoQingRenUserInfo($uid){
	     $sql=sprintf("SELECT a.`user` as xiaoshouloginname,a.department_id as department_id, b.`name`as bumenname,c.department_name from cp_zjingjiren_admin as a LEFT JOIN cp_zjingjiren_admin_group as b ON a.gid=b.id LEFT JOIN zx_department as c ON c.department_id=a.department_id where a.id=%s",$uid);
	     return  $this->dao->db->get_one_sql($sql);
	}
	/************************************************************
	 * @copyright(c): 2017年3月22日
	 * @Author:  yuwen
	 * @Create Time: 下午3:45:07
	 * @qq:32891873
	 * @email:fuyuwen88@126.com
	 * @获取用户名信息
	 *************************************************************/
	public function getUserinfo($uid){
	    $sql=sprintf("select a.`id` as investor_id,a.`username` as investor_login_name,c.`UsrName` as investor_real_name,b.`phone` as investor_cellphone,a.`create_time`,d.`friends` from cp_user as a LEFT JOIN cp_user_info as b ON b.uid=a.id LEFT JOIN cp_user_huifu as c ON c.uid=a.id LEFT JOIN cp_user_yaoqingma_list as d ON d.uid=a.id where a.id=%s",$uid);
	    return  $this->dao->db->get_one_sql($sql);
	}
	
	/************************************************************
	 * @copyright(c): 2017年3月22日
	 * @Author:  yuwen
	 * @Create Time: 下午4:36:42
	 * @qq:32891873
	 * @email:fuyuwen88@126.com
	 * @独立获取销售人员的真实姓名
	 * @这里的uid是线销售系统的uid，不是后台系统的uid注意注意
	 *************************************************************/
	public function getXiaoShouName($uid){
	    $sql=sprintf("SELECT a.`username` as baihedailoginname,b.UsrName from cp_user as a LEFT join  cp_user_huifu as b ON b.uid=a.id where a.id=%s",$uid);
	    return  $this->dao->db->get_one_sql($sql);
	}
	/************************************************************
	 * @copyright(c): 2017年3月22日
	 * @Author:  yuwen
	 * @Create Time: 下午4:49:27
	 * @qq:32891873
	 * @email:fuyuwen88@126.com
	 * @判断用户是否投资
	 * $uid传入用户uid
	 *************************************************************/
	public function IsUserWhethertoinvest($uid){
	    $sql=sprintf("SELECT id from cp_deal_order WHERE id=%s and `status`=2",$uid);
	    $res = $this->dao->db->get_one_sql($sql);
	    if(!empty($res)){
	        return true;
	    }else{
	        return false;
	    }
	}
	
}
