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
				'cl'=>array('name' =>'cl' ,'type' => 'string', 'require'=>false),
				'wl'=>array('name' =>'wl' ,'type' => 'string', 'require'=>false),
				),
			);
	}

	public function data(){
		//$rs=array('code'=> 0,'lat'=>'','lon'=>'','radius'=>'','address'=>'');
		//$url='cl='.$this->cl.'&wl='.$this->wl;
		$domain=new Domain_Lbs();
		$data=$domain->get_data($this->cl,$this->wl);
		if(empty($data)){
			return null;
		}
		$rs=json_decode($data,true);
		return $rs;
	}

}
