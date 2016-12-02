<?php

/**
*author wzb<wzb@lib-x.com>
*/
class Api_PushNovatech extends PhalApi_Api
{

	public function getRules()
	{

		return array(
			'polling'=>array(
				'push_id'=>array('name' =>'id' ,'type' => 'int', 'min' => 0,'require'=>true),
				),
				'location_upload'=>array(
						'imei'=>array('name' =>'imei' ,'min' => 0,'max' => 16,'require'=>true),
						'time'=>array('name' =>'time' ,'type' => 'date','require'=>true),
						'lon'=>array('name' =>'lon' ,'require'=>true),
						'lat'=>array('name' =>'lat' ,'require'=>true),
						'location'=>array('name' =>'location' ,'require'=>true),
						
				),
			);
		
	}
	
	
	
	
	
	
	
	
	
	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Dec 2, 2016 11:10:49 AM
	* pad 上传定位数据
	*/
	
	public function location_upload()
	{
		$rs=array('code'=> 0,'message'=>array(),'info'=>'');
		$domain=new Model_PushNovatech();
		$rs=$domain->location_upload($this->imei,$this->time,$this->lon,$this->lat,$this->location);
		return $rs;
	}
	

	public function polling()
	{

		$rs=array('code'=> 0,'message'=>array(),'info'=>'');
		$domain=new Domain_PushNovatech();
		$poll=$domain->polling($this->push_id);

		if(empty($poll)){
			//$rs['info']="no data";
			//return $rs;
			return null;
		}

		$rs['message']=$poll;

		return $rs;
	}
}
