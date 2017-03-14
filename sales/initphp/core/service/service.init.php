<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/*********************************************************************************
 * Service-service Service服务类基类
***********************************************************************************/
class serviceInit {
	
	/**
	 *	字段校验-用于进入数据库的字段映射
	 *  Service中使用方法：$this->service->parse_data($field, $data)
	 * 	@param  array   $field   可信任字段 array(array('field', 'int'))
	 * 	@param  array   $data    传入的参数
	 *  @return object
	 */
	public function parse_data($field, $data) {
		$field = (array) $field;
		$temp = array();
		foreach ($field as $val) {
			if (isset($data[$val[0]])) {
				if ($val[1] == 'int') {
					$data[$val[0]] = (int) $data[$val[0]];
				} elseif ($val[1] == 'obj') {
					$data[$val[0]] = serialize($data[$val[0]]);
				}
				$temp[$val[0]] = $data[$val[0]];
			}	
		}
		return $temp;
	}
	
	/**
	 *	service特殊情况-数据返回组装器
	 *  Service中使用方法：$this->service->return_msg($status, $msg, $data = '')
	 * 	@param  int    $status   返回参数状态
	 * 	@param  string $msg      提示信息
	 * 	@param  string $data     传递的参数
	 *  @return object
	 */
	public function return_msg($status, $msg, $data = '') {
		return array($status, $msg, $data);
	}
	
}
