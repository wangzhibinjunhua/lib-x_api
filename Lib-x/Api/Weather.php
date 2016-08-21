<?php
/**
* @author wzb<wangzhibin_x@foxmail.com>
* @date Aug 21, 2016 3:32:41 PM
* 天气服务,根据城市查询天气数据
*/

class Api_Weather extends PhalApi_Api
{
	public function getRules()
	{
	
		return array(
				'get_weather_info'=>array(
						'cityname'=>array('name' =>'cityname' ,'type' => 'string','max'=>12,'require'=>true),
				),
		);
	}
	
	public function get_weather_info()
	{
		$domain=new Domain_Weather();
		$data=$domain->get_weather_by_cityname($this->cityname);
		if(empty($data)){
			return null;
		}
		$rs=json_decode($data,true);
		return $rs;
	}
	
}