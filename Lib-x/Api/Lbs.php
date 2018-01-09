<?php

/**
*author wzb<wzb@lib-x.com>
*/
class Api_Lbs extends PhalApi_Api
{

	public function getRules()
	{

		return array(
			'data'=>array(
				'bts'=>array('name' =>'bts' ,'type' => 'string', 'require'=>true),
				'nearbts'=>array('name' =>'nearbts' ,'type' => 'string', 'require'=>false),
				'macs'=>array('name' =>'macs' ,'type' => 'string', 'require'=>false),
				),
			);
	}

	public function data(){
		//$rs=array('code'=> 0,'lat'=>'','lon'=>'','radius'=>'','address'=>'');
		//$url='cl='.$this->cl.'&wl='.$this->wl;
		$domain=new Domain_Lbs();
		$data=$domain->get_data($this->bts,$this->nearbts,$this->macs);
		if(empty($data)){
			return null;
		}
		$rs=json_decode($data,true);
		if($rs['status'] != 1){
			return null;
		}
		return $rs;
	}

}
