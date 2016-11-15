<?php
class Model_HaWatchAccount extends PhalApi_Model_NotORM
{

	
	public function reset_forgetpw($mobile,$vkey,$password)
	{
		$nData['password'] = strtoupper(md5($password));
		$nData['vkey'] = Common_Lib::getuuid();
		
		$where['mobile']=$mobile;
		$where['vkey']=$vkey;
		
		$r=$this->getORM('ha_watch','app_user')
		        ->where($where)
		        ->update($nData);
		
		if($r){
			return array('code'=>0,'message'=>'修改成功','info'=>'');
		}else{
			return array('code'=>1,'message'=>'修改失败','info'=>'');
		}
		
	}
	
	
	public function verify_forgetpw_code($mobile,$vcode)
	{
		$where['mobile'] = $mobile;
		$where['v_code'] = $vcode;
		$where['status'] = 0;
		$where['type'] = 2;//找回密码验证码
		$sms_id=$this->getORM('ha_watch','sms')
					 ->select('sms_id')
					 ->where($where)
					 ->fetchOne('sms_id');
	
		if($sms_id){
			//更改验证马短信状态
			$data['status'] = 1;
			$this->getORM('ha_watch','sms')
			->where('sms_id',$sms_id)
			->update($data);
	
			//生成临时key
			$vkey = md5($vcode.'-'.$mobile);
			
			
			$nData['vkey'] =$vkey;
			
			
			$r = $this->getORM('ha_watch','app_user')->update($nData);
			if($r){
				return array('code'=>0,'message'=>array('moblie'=>$mobile,'vkey'=>$vkey),'info'=>'');
			}else{
				return array('code'=>1,'message'=>'验证失败','info'=>'');
			}
	
		}else{
			return array('code'=>2,'message'=>'验证失败','info'=>'');
		}
	}
	
	

	public function get_forgetpw_code($mobile)
	{
		$where['mobile']=$mobile;
		$where['status']=1;
		$user=$this->getORM('ha_watch','app_user')
					   ->where($where)
					   ->fetchOne();
		if(!$user){
			return array('code'=>3,'message'=>'没有此用户','info'=>'');
		}
		
		$type = 2;
		$timesPerDay = 5;
		$intervalSecond = 60;
		$templateId = 'smsTpl:e7476122a1c24e37b3b0de19d04ae903';
		$templateStr = '[华英智联] 您正在使用找回密码功能，验证码是vCode';
		
		$r=self::send_sms_code($mobile, $type, $timesPerDay, $intervalSecond, $templateId, $templateStr);
		if($r == 0){
			return array('code'=>0,'message'=>'短信验证码已发送','info'=>'');
		}else{
			return array('code'=>1,'message'=>'稍后重试','info'=>'');
		}
	}
	
	
	public function login($mobile,$password)
	{
		$where['mobile']=$mobile;
		$where['password']=strtoupper(md5($password));

		$userinfo=$this->getORM('ha_watch','app_user')
					   ->where($where)
					   ->fetchOne();
		$userinfo=Common_Lib::delArrayItems($userinfo,'id,password,vkey,token,hid,app_sn,create_time,status');
		if($userinfo){
			if($userinfo['status']==2){
				return array('code'=>9,'message'=>'帐号被锁定','info'=>'');
			}
			//生成登录状态票据
			$data['token'] = Common_Lib::getuuid();//session_id()
			$where['mobile'] = $mobile;
			$r = $this->getORM('ha_watch','app_user')
					   ->where($where)
					   ->update($data);

			if(!$r){
				return array('code'=>3,'message'=>'登录失败','info'=>'');
			}
			$userinfo['token'] = $data['token'];
			return array('code'=>0,'message'=>$userinfo,'info'=>'');
		}else{
			return array('code'=>2,'message'=>'登录失败','info'=>'');
		}
	}


	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Oct 28, 2016 3:41:03 PM
	* 用户注册
	*/
	public function register($mobile,$vkey,$password)
	{
		$user=$this->getORM('ha_watch','app_user')
				   ->select('mobile')
				   ->where('mobile=?',$moblie)
				   ->where('status>?',0)
				   ->fetchOne('mobile');
		if($user){
			return array('code'=>1,'message'=>'此手机号已经注册过','info'=>'');
		}

		$data['password'] = strtoupper(md5($password));
		$data['status'] = 1;
		$where['mobile'] = $mobile;
		$where['vkey'] = $vkey;

		$r=$this->getORM('ha_watch','app_user')
				->where($where)
				->update($data);

		if($r){
			return array('code'=>0,'message'=>'注册成功','info'=>'');
		}else{
			return array('code'=>2,'message'=>'注册失败','info'=>'');
		}
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Oct 27, 2016 7:46:25 PM
	* 验证短信验证码
	*/
	public function verify_mobile($mobile,$vcode)
	{
		$where['mobile'] = $mobile;
		$where['v_code'] = $vcode;
		$where['status'] = 0;
		$where['type'] = 1;//注册验证码
		$sms_id=$this->getORM('ha_watch','sms')
				 ->select('sms_id')
				 ->where($where)
				 ->fetchOne('sms_id');

		if($sms_id){
			//更改验证马短信状态
			$data['status'] = 1;
			$this->getORM('ha_watch','sms')
				 ->where('sms_id',$sms_id)
				 ->update($data);

			//生成临时key
			$vkey = md5($vcode.'-'.$mobile);
			//生成一条新用户记录
			$nData['mobile'] = $mobile;
			$nData['vkey'] =$vkey;
			$nData['status'] = 0;
			$nData['create_time'] = date ( 'Y-m-d H:i:s' );
			$nData['hid'] =Common_Lib::getuuid();
			$r = $this->getORM('ha_watch','app_user')->insert($nData);
			if($r){
				return array('code'=>0,'message'=>array('moblie'=>$mobile,'vkey'=>$vkey),'info'=>'');
			}else{
				return array('code'=>1,'message'=>'验证失败','info'=>'');
			}

		}else{
			return array('code'=>2,'message'=>'验证失败','info'=>'');
		}
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Oct 27, 2016 7:46:47 PM
	* 查看手机号是否已注册过
	*/
	public function is_mobile_available($mobile)
	{
		$sql='select id from watch_app_user where status=:status and mobile=:mobile';
		$params=array(':status'=>1,':mobile'=>$mobile);
		if($this->getORM('ha_watch','app_user')
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
		$todayStart = Common_Lib::toDateUnix($today.' 00:00:00');
		$todayEnd = Common_Lib::toDateUnix($today.' 23:59:59');
		$sql='select sms_id from watch_sms where type='.$type.' and mobile='.$mobile.' and send_status=1 AND unix_time>='.$todayStart.' AND unix_time<='.$todayEnd.' ';
		$todayTimes=$this->getORM('ha_watch')
		->queryAll($sql);

		//var_dump(count($todayTimes));
		$todayTimes=count($todayTimes);
		if($todayTimes>=$timesPerDay){
			return 2;//超过次数
		}

		$sql='select unix_time from watch_sms where type='.$type.' and mobile='.$mobile.' and send_status=1 order by unix_time desc limit 0 ,1';

		$lastUnixTime = $this->getORM('ha_watch')
		->queryAll($sql);
		if($lastUnixTime){
			$lastUnixTime=$lastUnixTime[0]['unix_time'];
		}else{
			$lastUnixTime=0;
		}
		$ss = time() - $lastUnixTime;

		if ($ss < $intervalSecond) {
			return 3;//60s 间隔内
		}
		$sys_time=date ( 'Y-m-d H:i:s' );
		//echo $sys_time."  aaatest".PHP_EOL;
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
	    $data['send_time'] =  $sys_time;
	    $data['unix_time'] = time();
		$r=$this->getORM('ha_watch')->insert($data);
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



	protected function getTableName($id)
	{
		return 'sms';
	}
}
