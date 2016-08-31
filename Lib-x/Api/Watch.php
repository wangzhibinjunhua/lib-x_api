<?php

/**
* @author wzb<wangzhibin_x@foxmail.com>
* @date Aug 31, 2016 10:59:07 AM
* 儿童手表的所有接口服务
*/
class Api_Watch extends PhalApi_Api
{
	public function getRules()
	{
		return array(
				
				//通用接口
				'*'=>array(
						'imei'=>array('name'=>'imei','min'=>15,'max'=>15,'require'=>true),
				),
				'hello'=>array(
						'a'=>array('name' =>'a' ,'require'=>true),
						'b'=>array('name' => 'b','type'=>'date'),
				),
		);

	}
	
	
	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Aug 31, 2016 11:23:25 AM
	* 获取最新的一次定位位置
	*/
	public function get_lastest_location(){
		
		$rs=array('code'=> 0,'message'=>array(),'info'=>'');
		$domain=new Domain_Watch();
		$lastest_location=$domain->get_lastest_location($this->imei);
		
		if(empty($lastest_location))
		{
			$rs['code']=1;
		}else{
			$rs['code']=0;
			$rs['message']=$lastest_location;
		}
		return $rs;
	}
	
	
}