<?php
class Model_Geocode extends PhalApi_Model_NotORM
{
	public function regeo($location)
	{
		//高德逆地理编码
		$url='http://restapi.amap.com/v3/geocode/regeo?location='.$location.'&key=7fea101018f20db09c162c6f2ff8b4bd';
		//var_export($url);
		$curl=new PhalApi_CUrl();
		$rs=$curl->get($url);
		return $rs;
	
	}
	
	protected function getTableName($id)
	{
	
	}
}
