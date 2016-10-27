<?php
class Api_Account extends PhalApi_Api
{

	public function getRules()
	{
		return array(
				'get_vcode'=>array(
						'mobile'=>array('name' =>'mobile' ,'require'=>true),
				),
		);
	}


	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Oct 26, 2016 5:28:53 PM
	* 提交手机号,服务端生成验证码发送到手机
	*/
	public function get_vcode()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');

		//查询手机号是否已经注册过的
		$model=new Model_Account();
		if($model->is_mobile_available($this->mobile)==1){
			$rs['code']=1;
			$rs['message']='此手机号已经注册过了';
			return $rs;
		}

		$type = 1;
		$timesPerDay = 5;
		$intervalSecond = 60;
		$templateId = 'smsTpl:e7476122a1c24e37b3b0de19d04ae901';
		$templateStr = '[华英智联] 您的验证码是vCode，如非本人操作，请忽略本短信';
		$code=$model->send_sms_code($this->mobile, $type, $timesPerDay, $intervalSecond, $templateId, $templateStr);
		$rs['code']=$code;

		return $rs;
	}

}
