<?php
class Model_Watch extends PhalApi_Model_NotORM
{
	
	public function get_lastest_location($imei)
	{
		$sql='select watch_time,location_lon,location_lat,location_type,location_content,battery from watch_info where imei = '.$imei.' order by id desc limit 0 ,1';
		return $this->getORM('watch')
		->queryAll($sql);
	}
	
	
	protected function getTableName($id)
	{
		return 'info';
	}
}