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
				'get_lastest_location'=>array(
						'imei'=>array('name' =>'imei' ,'min' => 0,'max' => 16,'require'=>true),				
				),
				'get_day_location'=>array(
						'imei'=>array('name' =>'imei' ,'min' => 0,'max' => 16,'require'=>true),
						'date'=>array('name' =>'date' ,'type'=>'date','require'=>true),
				),
			);
		
	}
	
	
	public function get_day_location()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		$model=new Model_PushNovatech();
		if(strtotime( date('Y-m-d', strtotime($this->date)) ) === strtotime( $this->date)){
			$rs=$model->get_day_location($this->imei,$this->date);
		}else{
			
			$rs= array('code'=> 2,'message'=>'日期参数错误','info'=>'');
		}
		
		$code=$rs['code']+1000;$success=true;$msg='';
		if($code==1000){
			$success=true;
		}else{
			$success=false;
			$msg=$rs['message'].' '.$rs['info'];
		}
		Common_Lib::report('PushNovatech','get_day_location',$success,$code,$msg );
	
		return $rs;
	}
	
	
	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Dec 2, 2016 11:47:41 AM
	* 获取最新的一次定位数据
	*/
	
	public function get_lastest_location()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		$model=new Model_PushNovatech();
		$rs=$model->get_lastest_location($this->imei);
		
		$code=$rs['code']+1000;$success=true;$msg='';
		if($code==1000){
			$success=true;
		}else{
			$success=false;
			$msg=$rs['message'].' '.$rs['info'];
		}
		Common_Lib::report('PushNovatech','get_lastest_location',$success,$code,$msg );
		
		return $rs;
	}
	
	
	
	
	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Dec 2, 2016 11:10:49 AM
	* pad 上传定位数据
	*/
	
	public function location_upload()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		$model=new Model_PushNovatech();
		$rs=$model->location_upload($this->imei,$this->time,$this->lon,$this->lat,$this->location);
		
		$code=$rs['code']+1000;$success=true;$msg='';
		if($code==1000){
			$success=true;
		}else{
			$success=false;
			$msg=$rs['message'].' '.$rs['info'];
		}
		Common_Lib::report('PushNovatech','location_upload',$success,$code,$msg );
		
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
