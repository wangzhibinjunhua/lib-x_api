<?php
/**
* @author wzb<wangzhibin_x@foxmail.com>
* @date Aug 21, 2016 6:49:57 PM
* 地理编码/逆地理编码
*/
class Api_Geocode extends PhalApi_Api
{
	public function getRules()
	{
	
		return array(
				'regeo'=>array(
						'location'=>array('name' =>'location' ,'type' => 'string','require'=>true),
				),
				'geo'=>array(
						'address'=>array('name' =>'address' ,'type' => 'string','require'=>true),
				),
		);
	}
	
	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Aug 21, 2016 6:51:43 PM
	* 地理编码
	*/
	public function geo()
	{
		return null;
	}
	
	
	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Aug 21, 2016 6:52:48 PM
	* 逆地理编码,输入经纬度坐标,输出结构化地理信息
	*/
	public function regeo()
	{
		$domain=new Domain_Geocode();
		$data=$domain->regeo($this->location);
		if(empty($data)){
			return null;
		}
		$rs=json_decode($data,true);
		//查询错误
		if($rs['status'] != 1){
			return null;
		}
		return $rs;
	}
	
	
}