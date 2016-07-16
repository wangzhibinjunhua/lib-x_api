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
				'id'=>array('name' =>'id' ,'require'=>true),
				),
			);
	}

	public function polling()
	{

		$rs=array('code'=> 0,'message'=>array(),'info'=>'');
		$domain=new Domain_PushNovatech();
		$bg=$domain->polling();

		if(empty($bg)){
			//$rs['info']="no data";
			//return $rs;
			return null;
		}

		$rs['message']=$bg;

		return $rs;
	}
}
