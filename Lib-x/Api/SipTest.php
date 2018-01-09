<?php
DI()->response->setResType(1);
class Api_SipTest extends PhalApi_Api
{
	
	public function getRules()
	{
		return array(
		
				'login'=>array(
						'username'=>array('name' =>'username' ,'require'=>true),
						'password'=>array('name' =>'password' ,'require'=>true),
				),
				'register'=>array(
						'mobile'=>array('name' =>'mobile' ,'min'=>11,'max'=>11,'require'=>true),
						'vkey'=>array('name' =>'vkey' ,'min'=>6,'require'=>true),
						'password'=>array('name' =>'password' ,'min'=>6,'require'=>true),
				),
		);
	}
	
	public function login()
	{

		$rs=array('code'=> 0,'message'=>'Login Failed','info'=>'');
		if($this->username=='admin' && $this->password == 'admin'){
			$rs['code']=1;
			$rs['message']="Login Succeed";
		}
		return $rs;
	}
	
	
	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Oct 28, 2016 3:36:34 PM
	* 注册新用户
	* @param mobile
	* @param vkey
	* @param password(明文,服务端加密)
	* @return string(json) ok/fail
	*/
	public function register()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		$model=new Model_Account();
		$rs=$model->register($this->mobile,$this->vkey,$this->password);
		return $rs;
	}
	
	



}
