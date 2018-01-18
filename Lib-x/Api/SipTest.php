<?php
DI ()->response->setResType ( 1 );
if (!empty($_GET['callback'])) {
	DI()->response = new PhalApi_Response_JsonP($_GET['callback']);
}
class Api_SipTest extends PhalApi_Api {
	public function getRules() {
		return array (
				
				'login' => array (
						'username' => array (
								'name' => 'username',
								'require' => true 
						),
						'password' => array (
								'name' => 'password',
								'require' => true 
						) ,
						'callback' => array (
								'name' => 'callback',
								'require' => true 
						) 
				),
				
				'setNetWorkStatic' => array (
						'ip' => array (
								'name' => 'ip',
								'require' => true
						),
						'mask' => array (
								'name' => 'mask',
								'require' => true
						),
						'gate' => array (
								'name' => 'gate',
								'require' => true
						),
						'dns1' => array (
								'name' => 'dns1',
								'require' => true
						),
						'dns2' => array (
								'name' => 'dns2',
								'require' => true
						)
				),
				'setSipInfo' => array (
						'username' => array (
								'name' => 'username',
								'require' => true
						),
						'displayname' => array (
								'name' => 'displayname',
								'require' => true
						),
						'account' => array (
								'name' => 'account',
								'require' => true
						),
						'password' => array (
								'name' => 'password',
								'require' => true
						),
						'enable' => array (
								'name' => 'enable',
								'require' => true
						),
						'server' => array (
								'name' => 'server',
								'require' => true
						),
						'port' => array (
								'name' => 'port',
								'require' => true
						)
				),
				'register' => array (
						'mobile' => array (
								'name' => 'mobile',
								'min' => 11,
								'max' => 11,
								'require' => true 
						),
						'vkey' => array (
								'name' => 'vkey',
								'min' => 6,
								'require' => true 
						),
						'password' => array (
								'name' => 'password',
								'min' => 6,
								'require' => true 
						) 
				) 
		);
	}
	public function login() {
		$rs = array (
				'code' => 0,
				'message' => 'Login Failed',
				'info' => '' 
		);
		if ($this->username == 'admin' && $this->password == 'admin') {
			$rs ['code'] = 1;
			$rs ['message'] = "Login Succeed";
		}
		return $rs;
	}
	public function getSysInfo() {
		$rs = array (
				'code' => 1,
				'model' => 'HA05',
				'hwv' => '1.0',
				'swv' => 'HA05_v01_20180109',
				'upt' => '28:37:22' 
		);
		return $rs;
	}
	
	public function getNetStatus()
	{
		$rs = array (
				'code' => 1,
				'type' => 'DHCP',
				'mac' => '00:a8:59:f5:d4:39',
				'ip' => '192.168.0.122',
				'mask' => '255.255.255.0',
				'gate'=>'192.168.0.1'
		);
		
		return $rs;
	}
	
	public function setNetWorkStatic()
	{
		$rs = array (
				'code' => 1,
				'message' => 'set ok',
				'info' => ''
		);
		return $rs;
	}
	
	public function setNetWorkDhcp()
	{
		$rs = array (
				'code' => 1,
				'message' => 'set ok',
				'info' => ''
		);
		return $rs;
	}
	
	public function getSipInfo()
	{

		$rs = array (
				'code' => 1,
				'status' => '已注册',
				'username' => '722563',
				'displayname'=>'722563',
				'account'=>'722563',
				'password'=>'123456',
				'enable'=>'1',
				'server'=>'sip.stelcom.cn',
				'port'=>'5060'
						
		);
		return $rs;
	}
	
	public function setSipInfo()
	{
		$rs = array (
				'code' => 1,
				'message' => 'set ok',
				'info' => ''
		);
		return $rs;
		
	}
	
	/**
	 *
	 * @author wzb<wangzhibin_x@foxmail.com>
	 *         @date Oct 28, 2016 3:36:34 PM
	 *         注册新用户
	 * @param
	 *        	mobile
	 * @param
	 *        	vkey
	 * @param
	 *        	password(明文,服务端加密)
	 * @return string(json) ok/fail
	 */
	public function register() {
		$rs = array (
				'code' => 0,
				'message' => '',
				'info' => '' 
		);
		$model = new Model_Account ();
		$rs = $model->register ( $this->mobile, $this->vkey, $this->password );
		return $rs;
	}
}
