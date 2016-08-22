<?php
class Model_Weather extends PhalApi_Model_NotORM
{
	public function get_weather_by_cityname($cityname)
	{
		//juhe天气服务
		$url='http://op.juhe.cn/onebox/weather/query?cityname='.$cityname.'&key=bae2d217b052805814df4be3d2ba0b00';
		//var_export($url);
		$curl=new PhalApi_CUrl();
		$rs=$curl->get($url);
		return $rs;
		
	}
	
	
	
	protected function getTableName($id)
	{

	}
}