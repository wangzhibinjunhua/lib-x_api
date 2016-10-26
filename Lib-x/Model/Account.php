<?php
class Model_Account extends PhalApi_Model_NotORM
{

	
	public function is_mobile_available($mobile)
	{
		$sql='select sms_id from sms where status=:status and mobile=:mobile';
		$params=array(':status'=>'1',':mobile'=>$mobile);
		if($this->getORM('watch')
		->queryAll($sql,$params)){
			return 1;
		}else{
			return 0;
		}
	}
	
	
	public function send_sms_code($mobile, $type, $timesPerDay, $intervalSecond, $templateId, $templateStr)
	{
		$timesPerDay = 5;
		$intervalSecond = 60;
		$today = date ( 'Y-m-d' );
		$todayStart = toDateUnix($today.' 00:00:00');
		$todayEnd = toDateUnix($today.' 23:59:59');
		$sql='select count(*) from sms where type=:type and mobile=:mobile and send_status=1 AND unix_time>=$todayStart AND unix_time<=$todayEnd';
		$params=array(':type'=>$type,':mobile'=>$mobile);
		$todayTimes=$this->getORM('watch')
		->queryAll($sql,$params);
		if($todayTimes>=$timesPerDay){
			return 2;//超过次数
		}
		
		$sql='select unix_time from sms where type=:type and mobile=:mobile and send_status=1 order by unix_time desc limit 0 ,1';
		$params=array(':type'=>$type,':mobile'=>$mobile);
		$lastUnixTime = $this->getORM('watch')
		->queryAll($sql,$params);
		$ss = time() - $lastUnixTime;
		$css = $intervalSecond - $ss;
		
		if ($ss < $intervalSecond) {
			return 3;//60s 间隔内
		}
		
		//生成验证码
		$vCode = mt_rand(100000, 999999);
		//发送到手机（发送短信代码写在这里）
		//$smsContent = '[华英智联] 您的验证码是'.$vCode.'，如非本人操作，请忽略本短信';
		$smsContent = str_replace('vCode', $vCode, $templateStr);
		$receiver = array($mobile);
		$res = self::send_sms($receiver, $vCode, $templateId);
		$res = json_decode($res);
		if($res->sendStat->successCount>0){
	        $data['send_status'] = 1;
	    }
	    //保存在服务端
	    $data['mobile'] = $mobile;
	    $data['type'] = $type;
	    $data['v_code'] = $vCode;
	    $data['content'] = $smsContent;
	    $data['send_time'] = date ( 'Y-m-d H:i:s' );
	    $data['unix_time'] = time();
	
		$r=$this->getORM('watch')->insert($data);
		if($r){
			return 0;//ok
		}else{
			return 4;//save db fail
		}
	}
	
	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Oct 26, 2016 7:02:15 PM
	* 调用百度短信服务发送短信
	*/
	
	private function send_sms($receiver, $vCode, $templateId){
		$vCode .= '';
		date_default_timezone_set('UTC');
		$AK = "a0f1749ca34440b590e2d3ffece025c7";
		$timestamp = date("Y-m-d")."T".date("H:i:s")."Z";
		$expirationPeriodInSeconds = "3600";
		$SK="3888e0e6d1574ebaad612a291d944a44";
		 
		$authStringPrefix = "bce-auth-v1"."/".$AK."/".$timestamp."/".$expirationPeriodInSeconds;
		$SigningKey=hash_hmac('SHA256',$authStringPrefix,$SK);
		$CanonicalHeaders1 = "host;"."x-bce-date";
		$CanonicalHeaders2 = "host:sms.bj.baidubce.com\n"."x-bce-date:".urlencode($timestamp);//
		 
		$CanonicalString = "";
		$CanonicalURI = "/v1/message";
		$CanonicalRequest = "POST\n".$CanonicalURI."\n".$CanonicalString."\n".$CanonicalHeaders2; //第二步
		$Signature = hash_hmac('SHA256',$CanonicalRequest,$SigningKey);
		$Authorization = "bce-auth-v1/{$AK}/".$timestamp."/{$expirationPeriodInSeconds}/{$CanonicalHeaders1}/{$Signature}";
		$url = "http://sms.bj.baidubce.com/v1/message";
		$data = array(
				'templateId' => $templateId,
				'receiver' => $receiver,
				'contentVar' => json_encode(array(
						'code' => $vCode
				))
		);
		$data_string = json_encode($data);
		$head =  array("POST /v1/message HTTP/1.1","Host: sms.bj.baidubce.com","Content-Type:application/json","Authorization:{$Authorization}","x-bce-date:{$timestamp}","x-bce-content-sha256:{$SigningKey}");
		$curlp = curl_init();
		curl_setopt($curlp, CURLOPT_URL, $url);
		curl_setopt($curlp, CURLOPT_HTTPHEADER,$head);
		curl_setopt($curlp, CURLOPT_POSTFIELDS, $data_string);
		if(!empty($data)){
			curl_setopt($curlp, CURLOPT_POST, 1);
		}
		curl_setopt($curlp, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curlp);
		curl_close($curlp);
		return $output;
	}
	
	
	public function get_lastest_location($imei)
	{
		$sql='select watch_time,location_lon,location_lat,location_type,location_content,battery from watch_info where imei = :imei order by id desc limit 0 ,1';
		$params=array(':imei'=> $imei);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}

	public function get_day_location($imei,$date)
	{
		$sql='select location_type,location_lon,location_lat from watch_info where imei = :imei and date(watch_time) = :datetime';
		$params=array(':imei'=> $imei,':datetime'=> $date);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}

	public function get_new_message_list($imei,$user_id)
	{
		$sql='select file from watch_message where flag=0 and user_id=:user_id and imei=:imei';
		$params=array(':user_id'=>$user_id,':imei'=>$imei);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}

	public function ignore_message($imei,$user_id,$filename)
	{
		$sql='update watch_message set flag=:flag where imei=:imei and user_id=:user_id and file=:filename';
		$params=array(':flag'=>'1',':imei'=>$imei,':user_id'=>$user_id,':filename'=>$filename);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}

	public function bind_watch($imei,$user_id)
	{
		$sql='select * from watch_app_watch where watch_imei=:imei and app_id=:id';
		$params=array(':imei'=>$imei,':id'=>$user_id);
		$test=$this->getORM('watch')->queryAll($sql,$params);
		if(!empty($test)){
			return null;
		}

		$data=array('watch_imei'=>$imei,'app_id'=>$user_id);
		$rs=$this->getORM('watch')
		->insert($data);
		return $rs;
		//return $this->getORM('watch')
		//->insert($data);
	}

	public function unbind_watch($imei,$user_id)
	{
		return $this->getORM('watch')
		->where('watch_imei',$imei)
		->where('app_id',$user_id)
		->delete();
	}

	protected function getTableName($id)
	{
		return 'sms';
	}
}
