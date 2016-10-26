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
		return $rs;
	}
	
}