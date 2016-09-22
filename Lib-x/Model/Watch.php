<?php
class Model_Watch extends PhalApi_Model_NotORM
{

	public function get_lastest_location($imei)
	{
		$sql='select watch_time,location_lon,location_lat,location_type,location_content,battery from watch_info where imei = :imei order by id desc limit 0 ,1';
		$params=array(':imei'=> $imei);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}

	public function get_day_location($imei,$date)
	{
		$sql='select location_lon,location_lat from watch_info where imei = :imei and date(watch_time) = :datetime';
		$params=array(':imei'=> $imei,':datetime'=> $date);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}
	
	public function get_new_message_list($user_id)
	{
		$sql='select stamp from watch_message where flag=0 and user_id=:user_id';
		$params=array(':user_id'=>$user_id);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}

	protected function getTableName($id)
	{
		return 'info';
	}
}
