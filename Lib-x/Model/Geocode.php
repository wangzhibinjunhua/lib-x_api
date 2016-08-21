<?php
class Model_Geocode extends PhalApi_Model_NotORM
{
	public function regeo($location)
	{
		//高德逆地理编码
		$url='http://restapi.amap.com/v3/geocode/regeo?location='.$location.'&key=7fea101018f20db09c162c6f2ff8b4bd';
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
