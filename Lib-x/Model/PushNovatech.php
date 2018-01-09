<?php
class Model_PushNovatech extends PhalApi_Model_NotORM
{

	public function get_day_location($imei,$date)
	{
		$where ['imei'] = $imei;
		$where ['date(dev_time)'] = $date;
		$where['location_lon != ?']='';
		$r = $this->getORM ( 'android_push', 'pad_info' )
		->select ( 'imei,location_lon,location_lat,location_content,dev_time' )
		->where ( $where )
		->order ( 'id DESC' )
		->fetchAll ();
		if($r){
			return array (
					'code' => 0,
					'message' => $r,
					'info' => ''
			);
		}else{
			return array('code'=>1,'message'=>'无数据','info'=>'');
		}
	}
	
	
	public function get_lastest_location($imei)
	{
		$where['imei']=$imei;
		$where['location_lon != ?']='';
		$where['location_lat != ?']='';
		$r = $this->getORM ( 'android_push', 'pad_info' )
					->select ( 'imei,location_lon,location_lat,location_content,dev_time' )
					->where ( $where )
					->order ( 'id DESC' )
					->limit ( 0, 1 )
					->fetchAll ();
		if($r){
			return array (
					'code' => 0,
					'message' => $r,
					'info' => ''
			);
		}else{
			return array('code'=>1,'message'=>'无数据','info'=>'');
		}
	}
	
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
