<?php

/**
*author wzb<wzb@lib-x.com>
*/
//sign
//DI()->filter = 'PhalApi_Filter_SimpleMD5';
//###########################
class Api_Welcome extends PhalApi_Api
{

	public function getRules()
	{
		//DI()->filter = 'Common_LibxFilter';
		return array(
			'hello'=>array(
				'a'=>array('name' =>'a' ,'require'=>true),
				'b'=>array('name' => 'b','type'=>'date'),
				),
			'get_bp'=>array(
				'a'=>array('name' =>'a' ,'require'=>true),
				'b'=>array('name' => 'b'),
				),
			);
	}
	
	public function get_watch_online_count()
	{
		Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
		$watch_count=Common_GatewayClient::getClientCountByGroup('watch_g1');
		$app_count=Common_GatewayClient::getClientCountByGroup('app_g1');
		$debug_count=Common_GatewayClient::getClientCountByGroup('debug1');
		$all_count=Common_GatewayClient::getAllClientCount();
		return '手表在线数量: '.$watch_count.' ; app在线数据: '.$app_count.'  ;web在线数量: '.$debug_count.
				'  ;总在线数量: '.$all_count;
	}

	public function hello()
	{

		Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
		Common_GatewayClient::sendToUid('201508220451111', '123aaa');
		$rs=array();
		$rs['a']=$this->a;
		$rs['b']=$this->b;
		$rs['试下中文']='乱码吗?';
		return $rs;
	}

	public function get_bg()
	{

		$rs=array('code'=> 0,'value'=>array(),'info'=>'');
		$domain=new Domain_Welcome();
		$bg=$domain->get_all();

		if(empty($bg)){
			$rs['info']="no data";
			return $rs;
		}

		$rs['value']=$bg;

		return $rs;
	}
}
