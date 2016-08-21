<?php
class Model_Weather extends PhalApi_Model_NotORM
{
	public function get_weather_by_cityname($cityname)
	{
		//高德基站定位
		$url='http://op.juhe.cn/onebox/weather/query?cityname='.$cityname.'&key=bae2d217b052805814df4be3d2ba0b00';
		//var_export($url);
		$client = Common_HttpClient::create()
		->withHost($url);
		$rs = $client->reset()
		->withTimeout(3000)
		->request();
		
		return $rs;
		
	}
	
	
	
	protected function getTableName($id)
	{

	}
}