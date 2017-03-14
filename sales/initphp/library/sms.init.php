<?php
/*********************************************************************************
 * 扩展类库-短信发送
***********************************************************************************/
class smsInit{
	private $cdkey;
	private $password;
	private $enabeLog = true; //日志开关。可填值：true、
	private $Filename="/home/wwwlogs/phoneCodelog.txt"; //日志文件
	private $Handle; 

	public function __construct()
	{
		$this->cdkey    = "9SDK-EMY-0999-RGXOQ";
		$this->password = "491976";
		$this->Handle = fopen($this->Filename, 'a');
	}
	
   /**
    * 打印日志
    * @param log 日志内容
    */
    private function showlog($log)
	{
      if($this->enabeLog) fwrite($this->Handle,$log."\n"); 
    }

	/**
	 * [send 发送数据]
	 * @param  integer  $type [类型]
	 * @param  [array]  $data [数据]
	 * @return [array]        [数据]
	 */
	private function send($type=1,$data=NULL)
	{
		header("Content-type: text/html; charset=utf-8");
		if($type == 1)
		{
			$t = 'sendsms';
			foreach($data as $v => $k)
			{
				$d .= '&'.$v.'='.$k;
			}
		}else{
			$t = 'querybalance';
			$d = '';
		}
		$url = 'http://sdk999ws.eucp.b2m.cn:8080/sdkproxy/'.$t.'.action?cdkey='.$this->cdkey.'&password='.$this->password.$d;
		$data = file_get_contents($url);
		preg_match('/<response>(.*?)<\/response>/',$data,$data);
		preg_match('/<error>(.*?)<\/error>/',$data[1],$redata['error']);
		if($type == 2)
		{
			preg_match('/<message>(.*?)<\/message>/',$data[1],$redata['message']);
			$redata['message'] = $redata['message'][1];
		}
		$redata['error'] = $redata['error'][1];
		$this->showlog("request url = ".$url);
		$this->showlog("response body = ".var_export($redata, true));
		return $redata;
	}

	/**
	 * [querybalance 获取余额]
	 * @return [type] [description]
	 */
	public function querybalance()
	{
		$data = $this->send(2);
		if($data['error'] == 0) return $data['message'];
		else return -1;
	}

	/**
	 * [msm_send 发送短信]
	 * @param  array   $phones   [手机号]
	 * @param  [type]  $content  [发送内容]
	 * @param  integer $sendTime [定时发送]
	 * @return [type]            [操作结果]
	 */
	public function msm_send($phones=array(),$content,$sendTime=0)
	{
		if(is_array($phones)){
			foreach($phones as $v){
				$phone .= $v.',';
			}
			$phone = trim($phone,',');
		}else{
			$phone = $phones;
		}
		$data['phone'] = $phone;
		$data['message'] = urlencode($this->auto_charset($content,"gbk",'utf-8'));
		if($sendTime){
			$data['sendtime'] = date('YmdHis',$sendTime);
		} 
		$redata = $this->send(1,$data);
		if($redata['error'] == 0){
			return true;
		}else{
			return false;
		}
	}

	private function is_utf8($string)
	{
		return preg_match( "%^(?:\r\n\t\t [\\x09\\x0A\\x0D\\x20-\\x7E]            # ASCII\r\n\t   | [\\xC2-\\xDF][\\x80-\\xBF]             # non-overlong 2-byte\r\n\t   |  \\xE0[\\xA0-\\xBF][\\x80-\\xBF]        # excluding overlongs\r\n\t   | [\\xE1-\\xEC\\xEE\\xEF][\\x80-\\xBF]{2}  # straight 3-byte\r\n\t   |  \\xED[\\x80-\\x9F][\\x80-\\xBF]        # excluding surrogates\r\n\t   |  \\xF0[\\x90-\\xBF][\\x80-\\xBF]{2}     # planes 1-3\r\n\t   | [\\xF1-\\xF3][\\x80-\\xBF]{3}          # planes 4-15\r\n\t   |  \\xF4[\\x80-\\x8F][\\x80-\\xBF]{2}     # plane 16\r\n   )*$%xs", $string );
	}

	private function auto_charset( $fContents, $from = "gbk", $to = "utf-8" )
	{
		$from = strtoupper( $from ) == "UTF8" ? "utf-8" : $from;
		$to = strtoupper( $to ) == "UTF8" ? "utf-8" : $to;
		if($to=="utf-8" && $this->is_utf8($fContents) || strtoupper( $from ) === strtoupper( $to ) || empty( $fContents ) || is_scalar( $fContents ) && !is_string( $fContents ) )
		{
			return $fContents;
		}
		if(is_string($fContents))
		{
			if(function_exists("mb_convert_encoding")) return mb_convert_encoding($fContents,$to,$from);
			else
			{
				if(function_exists("iconv")) return iconv($from,$to,$fContents);
				else return $fContents;
			}
		}
		else if(is_array($fContents))
		{
			foreach($fContents as $key=>$val)
			{
				$_key = auto_charset($key,$from,$to);
				$fContents[$_key] = auto_charset($val,$from,$to);
				if($key!=$_key) unset($fContents[$key]);
			}
			return $fContents;
		}
		else
		{
			return $fContents;
		}
	}
}