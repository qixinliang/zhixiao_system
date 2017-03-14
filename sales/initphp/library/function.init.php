<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');   
/*********************************************************************************
 * 扩展类库-方法库
***********************************************************************************/
class functionInit {

	/**
	 * 方法库-sign签名方法
	 * @param $array 需要加密的参数 
	 * @param $secret 秘钥
	 * @param $signName sign的名称，sign不会进行加密
	 */
	public function sign($array, $secret, $signName = "sign") {
		if (count($array) == 0) {
			return "";
		}
		ksort($array); //按照升序排序
		$str = "";
		foreach ($array as $key => $value) {
			if ($signName == $key) continue;
			$str .= $key . "=" . $value . "&";
		}
		$str = rtrim($str, "&");
		return md5($str . $secret);
	}

	/**
	 * 方法库-获取随机值
	 * @return string  
	 */
	public function get_rand($str, $len) {
		return substr(md5(uniqid(rand()*strval($str))),0, (int) $len);
	}
	
	/**
	 * 方法库-获取随机Hash值 
	 * @return string
	 */
	public function get_hash($length = 13) { 
		$chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    	$max = strlen($chars) - 1;
    	mt_srand((double)microtime() * 1000000);
		for ($i=0; $i<$length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
		return $hash;
	}
	
	/**
	 * 方法库-截取字符串-【该函数作者未知】
	 * @param string  $string 字符串  
	 * @param int     $length 字符长度
	 * @param string  $dot    截取后是否添加...
	 * @param string  $charset编码
	 * @return string
	 */
	public function cutstr($string, $length, $dot = '...', $charset = 'utf-8') {

		if (strlen($string)/2 <= $length) {
			return $string;
		}
		$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
		$strcut = '';
		if (strtolower($charset) == 'utf-8') {
			$n = $tn = $noc = 0;
			while ($n < strlen($string)) {
				$t = ord($string[$n]);				//ASCIIֵ
				if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1; $n++; $noc++;
				} elseif (194 <= $t && $t <= 223) {
					$tn = 2; $n += 2; $noc += 2;
				} elseif (224 <= $t && $t < 239) {
					$tn = 3; $n += 3; $noc += 2;
				} elseif (240 <= $t && $t <= 247) {
					$tn = 4; $n += 4; $noc += 2;
				} elseif (248 <= $t && $t <= 251) {
					$tn = 5; $n += 5; $noc += 2;
				} elseif ($t == 252 || $t == 253) {
					$tn = 6; $n += 6; $noc += 2;
				} else {
					$n++;
				}
				if($noc >= $length) {
					break;
				}
			}
			if ($noc > $length) {
				$n -= $tn;
			}
			$strcut = substr($string, 0, $n);
		} else {
			for ($i = 0; $i < $length; $i++) {
				$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
			}
		}
		$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
		return $strcut.$dot;
	}
	
	/**
	 * 方法库-字符串是否存在
	 * @param string $str :字符或字符串
	 * @param string $string :字符串
	 * @return string 例子: $str='34' $string='1234' 返回 TRUE
	 */
	public function is_str_exist($str, $string) {
		$string = (string) $string;
		$str = (string) $str;
		return strstr($string,$str)===false ? false : true;
	}
	
	/**
	 * 方法库-token使用
	 * @param string $type :encode-加密方法|decode-解密方法
	 * @return string|bool
	 */
	public function token($type = 'encode') {
		session_start();
		if ($type == 'encode') {
			$key = $this->get_hash(5);
			$_SESSION['init_token'] = $key;
			return '<input name="init_token" type="hidden" value="'.$_SESSION['init_token'].'"/>';
		} else {
			$value = trim($_POST['init_token']);
			if ($value !== $_SESSION['init_token']) return false;
			return true;
		}
	}
	
	/**
	 * 方法库-压缩函数，主要是发送http页面内容过大的时候应用
	 * @param string $content 内容
	 * @return string
	 */
	public function gzip(&$content) {
		if(!headers_sent()&&extension_loaded("zlib")&&strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")){
			$content = gzencode($content,2);
			header("Content-Encoding: gzip");
			header("Vary: Accept-Encoding");
			header("Content-Length: ".strlen($content));
		}
		return $content;
	}

    /**
     * 方法库-获取客户端IP地址
     */
    function get_client_ip(){
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
            $ip = getenv("HTTP_CLIENT_IP");
        }else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
            $ip = getenv("REMOTE_ADDR");
        }else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else{
            $ip = "unknown";
        }
        if (preg_match('#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $ip)) {
            $ip_array = explode('.', $ip);
            if($ip_array[0]<=255 && $ip_array[1]<=255 && $ip_array[2]<=255 && $ip_array[3]<=255){
                return $ip;
            }
        }
        return "unknown";
    }
	
	/**
	 * 方法库-向父串中插入子串
	 * @param string $string  : 父串
	 * @param number $sublen  : 长度
	 * @param string $str     : 子串
	 * @param string $code    : 编码
	 * @return string
	 */
	public function insert_str($string, $sublen=10, $str="<br/>", $code='UTF-8'){
		if ($code == 'UTF-8') {
			$pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($pa, $string, $t_string);
			$n = count($t_string[0]);
			$floor = ceil($n / $sublen);
			if ($n > $sublen) {
				for ($i=0; $i < $floor; $i++) {
					array_splice($t_string[0], ($sublen * ($i+1))-1, 0, $str);
				}
				return implode('',  $t_string[0]);
			} else {
				array_splice($t_string[0], $sublen, 0);
				return implode('', $t_string[0]);
			}
		}
	}
	
	/**
	 * 方法库-加密解密函数
	 * @param string $string  加密的字符串
	 * @param number $key     加密的密钥
	 * @param string $type    加密的方法-ENCODE|加密 DECODE|解密
	 * @return string
	 */
	public function str_code($string, $key, $type = 'ENCODE') {
		$string = ($type == 'DECODE') ? base64_decode($string) : $string;
		$key_len = strlen($key);
		$key     = md5($key);
		$string_len = strlen($string);
		for ($i=0; $i<$string_len; $i++) {
			$j = ($i * $key_len) % 32;
			$code .= $string[$i] ^ $key[$j];
		}
		return ($type == 'ENCODE') ? base64_encode($code) : $code;
	}
	
	/**
	 * 方法库-输出钱的格式
	 * @param string $num  数值
	 * @return string
	 */
	public function format_number($num){
   		return number_format($num, 2, ".", ",");
	}

	/**
	 * 方法库-字节转换-转换成MB格式等
	 * @param string $num  数值
	 * @return string
	 */
	public function bitsize($num) {
		if(!preg_match("/^[0-9]+$/", $num)) return 0;
		$type = array( "B", "KB", "MB", "GB", "TB", "PB" );
		$j = 0;
		while($num >= 1024) {
    		if( $j >= 5 ) return $num.$type[$j];
    		$num = $num / 1024;
    		$j++;
   		}
   		return $num.$type[$j];
	}
	
	/**
	 * 方法库-数组去除空值
	 * @param string $num  数值
	 * @return string
	 */
	public function array_remove_empty(&$arr, $trim = true) {
		if (!is_array($arr)) return false;
		foreach($arr as $key => $value){
			if (is_array($value)) {
				self::array_remove_empty($arr[$key]);
			} else {
				$value = ($trim == true) ? trim($value) : $value;
				if ($value == "") {
					unset($arr[$key]);
				} else {
					$arr[$key] = $value;
				}
			}
		}
	}
	
   /**
	* 生成 options html 代码
	* @param array  $arr 健值数组
	* @param string $default 默认值
	* @return string htmlcode
	*/
	function generateHtmlOptions($arr, $default = null){
		$string = '';
		if (!is_array($arr) && count($arr)) return $string;
		foreach ($arr as $key => $val) {
			$selected = ($default == $key) ? 'selected' : '';
			$string .= '<option value="'.$key.'" '.$selected.'>';
			$string .=  $val;
			$string .= '</option>';
		}
		return $string;
	}
	
	/**
	 * 清空数组中的空值
	 * @param array $array
	 * @return array
	 */
	public function clear_array_null(&$array) {
		foreach($array as $k => $v){
			if(empty($v)) unset($array[$k]);
		}
		return $array;
	}
	
	/**
	 * 左边清空
	 * @param string $string
	 * @return string
	 */
	public function rltrim($string) {
		if (is_string($string)) return trim($string);
		foreach($string as $k => $v){
			$string[$k] = trim($v);
		}
		return $string;
	}
	
	/**
	 * 生成唯一的订单号 20110809111259232312
	 * 2011-年日期
	 * 08-月份
	 * 09-日期
	 * 11-小时
	 * 12-分
	 * 59-秒
	 * 2323-微秒
	 * 12-随机值
	 * @return string
	 */
	public function trade_no() {
		list($usec, $sec) = explode(" ", microtime());
		$usec = substr(str_replace('0.', '', $usec), 0 ,4);
		$str  = rand(10,99);
		return date("YmdHis").$usec.$str;
	}
	
	/**
     * 获取接受JS传递中文编码函数
     * 作者：Min
     * @param string $str
     * @return string 
     */
    public function js_unescape($str){  
        $ret = '';  
        $len = strlen($str);  
      
        for ($i = 0; $i < $len; $i++) {  
            if ($str[$i] == '%' && $str[$i+1] == 'u') {  
                $val = hexdec(substr($str, $i+2, 4));  
                if ($val < 0x7f) $ret .= chr($val);  
                else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));  
                else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));  
                $i += 5;  
            } else if ($str[$i] == '%') {  
                $ret .= urldecode(substr($str, $i, 3));  
                $i += 2;  
            }  
            else $ret .= $str[$i];  
        }  
        return $ret;  
    }

    /*
     * 作 用：检查数据是否是99999.99格式
     * 参 数：$C_Money（待检测的数字）
     * 返回值：布尔值
     */
    public function ISMoney($Money)
    {
        if (preg_match("/^[0-9]*\.?[0-9]{0,2}$/",$Money))
        {
            return $Money ;
        }
        return false;
    }

    /*
     * 随机激活码
     */
    function authcode($string, $operation = 'DECODE', $key = '', $expiry = 3600) {

        $ckey_length = 4;
        // 随机密钥长度 取值 0-32;
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥
        $key = md5 ( $key ? $key : 'key' ); //这里可以填写默认key值
        $keya = md5 ( substr ( $key, 0, 16 ) );
        $keyb = md5 ( substr ( $key, 16, 16 ) );
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), - $ckey_length )) : '';

        $cryptkey = $keya . md5 ( $keya . $keyc );
        $key_length = strlen ( $cryptkey );

        $string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;
        $string_length = strlen ( $string );

        $result = '';
        $box = range ( 0, 255 );

        $rndkey = array ();
        for($i = 0; $i <= 255; $i ++) {
            $rndkey [$i] = ord ( $cryptkey [$i % $key_length] );
        }

        for($j = $i = 0; $i < 256; $i ++) {
            $j = ($j + $box [$i] + $rndkey [$i]) % 256;
            $tmp = $box [$i];
            $box [$i] = $box [$j];
            $box [$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i ++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box [$a]) % 256;
            $tmp = $box [$a];
            $box [$a] = $box [$j];
            $box [$j] = $tmp;
            $result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
        }

        if ($operation == 'DECODE') {
            if ((substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 )) {
                return substr ( $result, 26 );
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace ( '=', '', base64_encode ( $result ) );
        }

    }

    //计算时间月份  项目期限
    /**
     * @param string $tenderCompletedDate The deal load full timestamp in string, sucn as 2014-08-08.
     * @param Char $periodType Whether 'D' or 'M', Day or Month
     * @param Integer $period Day number, or Month number
     * @param Integer $dueDate The deal's due date, this is timestamp in integer
     * @param Boolean or Integer (0|1) $amortized 该借款是否属于分期偿付
     */

    function LoanTerms($tenderCompletedDate=null, $period=null, $dueDate=null, $periodType='d', $amortized = true)
    {
        defined('DEAL_PERIOD_TYPE_DAY') || define('DEAL_PERIOD_TYPE_DAY', 'D');
        defined('DEAL_PERIOD_TYPE_MONTH') || define('DEAL_PERIOD_TYPE_MONTH', 'M');
        $ret = null;
        $tz = new DateTimeZone('Asia/Shanghai');
        try {
            if (!$period && !$dueDate) throw new Exception('Error: The deal\'s period and due date is null.');
            $periodType = $periodType ? strtoupper($periodType) : null;
            if (!$periodType) throw new Exception('Error: The period type not defined.');
            if ($periodType != DEAL_PERIOD_TYPE_DAY && $periodType != DEAL_PERIOD_TYPE_MONTH) throw new Exception('Error: The period type must be \'d\' for day, or \'m\' for month.');
            if ($dueDate)
            {
                $dt = new DateTime();
                $dt->setTimestamp($dueDate);
                $dt->setTimezone($tz);
                $dueDate=$dt;
            }
            if ($tenderCompletedDate)
            {
                $dt = new DateTime($tenderCompletedDate, $tz);
                $tenderCompletedDate = $dt;
            }
            if ($tenderCompletedDate)
            {
                if (!$dueDate)
                {
                    $dueDate = new DateTime();
                    $dueDate->setTimestamp($tenderCompletedDate->format('U'));
                    $dueDate->setTimezone($tz);
                    $dueDate->add(new DateInterval(sprintf("P%s%s", $period, $periodType)));
                }
                $period = $tenderCompletedDate->diff($dueDate);
                if (!$period->invert)
                {
                    if ($amortized)
                    {
                        $monthNumber = 0;
                        if ($period->y) $monthNumber += $period->y * 12;
                        $monthNumber += $period->m;
                        if ($monthNumber)
                        {
                            $formatStr = $tenderCompletedDate->format('Ymd') == $tenderCompletedDate->format('Ymt') ? 'Y-m-t' : 'Y-m-d';
                            for($i=1;$i<=$monthNumber;$i++)
                            {
                                if ($i == 1) $lastDT = $tenderCompletedDate;
                                $lastU = $lastDT->format('U');
                                $lastDT->add(new DateInterval('P1M'));
                                $nextU = $lastDT->format('U');
                                $last = new DateTime();
                                $last->setTimestamp($lastU);
                                $last->setTimezone($tz);
                                $next = new DateTime();
                                $next->setTimestamp($nextU);
                                $next->setTimezone($tz);
                                $dt1 = new DateTime($last->format($formatStr), $tz);
                                $dt2 = new DateTime($next->format($formatStr), $tz);
                                if ($dt2->format('Ym') == $dueDate->format('Ym') && $tenderCompletedDate->format('Ymd') == $tenderCompletedDate->format('Ymt') ) $dt2 = $dueDate;
                                $ret['days'][$i] = array('date'=>$dt2->format('U'), 'length'=>$dt1->diff($dt2)->days, 'period'=>array('y'=>$period->y, 'm'=>$period->m, 'd'=>$period->d, 'days'=>$period->days));
                            }
                        }
                        if ($period->d)
                        {
                            $key = 1;
                            if (isset($ret['days']) && $ret['days'])
                            {
                                $key = count($ret['days'])+1;
                            }
                            $ret['days'][$key] = array('date'=>$dueDate->format('U'), 'length'=>$period->d, 'period'=>array('y'=>$period->y, 'm'=>$period->m, 'd'=>$period->d, 'days'=>$period->days));
                        }
                    }
                    else
                    {
                        $ret['days'][1] = array('date'=>$dueDate->format('U'), 'length'=>$period->days, 'period'=>array('y'=>$period->y, 'm'=>$period->m, 'd'=>$period->d, 'days'=>$period->days));
                    }
                }
            }
            else
            {
                if (!$dueDate)
                {
                    $dueDate = new DateTime();
                    $dueDate->setTimezone($tz);
                    $dueDate->add(new DateInterval(sprintf("P%s%s", $period, $periodType)));
                }
                $now = new DateTime();
                $now->setTimezone($tz);
                $period = $now->diff($dueDate);
                $ret = array('period'=>array('y'=>$period->y, 'm'=>$period->m, 'd'=>$period->d, 'days'=>$period->days));
            }
            if ($ret && isset($ret['days']) && $ret['days']) $ret['count'] = count($ret['days']);
            return $ret;
        }
        catch(Exception $e) {
            exit($e->getMessage());
        }
    }
	/*
     * 作 用：post提交表单
     * 参 数：$params 要提交的数据，数组格式
     * 返回值：无 直接跳转
     */
  	public function post_huifu($params)
	{
	?>
		<html>
		<head></head>
		<body onload='load()'>
		<form id='tradeForm' action="<?php echo $params['url']?>" method='post'>
		<?php
			foreach($params['data'] as $key=>$val)
			{
				echo "<input type='hidden' id='".$key."' name='".$key."' value='".$val."' />"; 
			}
		?>
		</form>
		<script type='text/javascript'>
		function load(){
		document.getElementById('tradeForm').submit();
		}
		</script>
		</body>
		</html>
	<?php }

}