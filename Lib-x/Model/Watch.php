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

	public function get_new_message_list($imei,$user_id)
	{
		$sql='select file from watch_message where flag=0 and user_id=:user_id and imei=:imei';
		$params=array(':user_id'=>$user_id,':imei'=>$imei);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}

	public function ignore_message($imei,$user_id,$filename)
	{
		$sql='update watch_message set flag=:flag where imei=:imei and user_id=:user_id and file=:filename';
		$params=array(':flag'=>'1',':imei'=>$imei,':user_id'=>$user_id,':filename'=>$filename);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}
	
	public function bind_watch($imei,$user_id)
	{
		$sql='insert into watch_app_watch (watch_imei,app_id) values (:imei,:id)';
		$params=array(':imei'=>$imei,':id'=>$user_id);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}
	
	public function unbind_watch($imei,$user_id)
	{
		$sql='delete from watch_app_watch where watch_imei=:imei and app_id=:id';
		$params=array(':imei'=>$imei,':id'=>$user_id);
		return $this->getORM('watch')
		->queryAll($sql,$params);
	}

	protected function getTableName($id)
	{
		return 'info';
	}
}
