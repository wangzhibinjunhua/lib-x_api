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
				'push_id'=>array('name' =>'id' ,'type' => 'int', 'min' => 1,'require'=>true),
				),
			);
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
