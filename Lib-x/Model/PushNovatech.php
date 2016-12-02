<?php
class Model_PushNovatech extends PhalApi_Model_NotORM
{

	public function location_upload($imei,$time,$lon,$lat,$location)
	{
		$data['imei']=$imei;
		$data['dev_time']=$time;
		$data['location_lon']=$lon;
		$data['location_lat']=$lat;
		$data['location_content']=$location;
		$data['sys_time']=date ( 'Y-m-d H:i:s' );
		$data['unix_time']=time();
		$r=$this->getORM('android_push','pad_info')
				->insert($data);
		if($r){
			return array('code'=>0,'message'=>'ok','info'=>'');
		}else{
			return array('code'=>1,'message'=>'fail','info'=>'');
		}
	}
	
	
	public function polling($id)
	{

		$sql='select * from push_novatech where id > :id order by id desc limit 0 ,1';
		$params=array(':id'=> $id);
		return $this->getORM('android_push')
			->queryAll($sql,$params);

	}

	protected function getTableName($id)
	{
		return 'novatech';
	}

}
