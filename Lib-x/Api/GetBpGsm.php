<?php

/**
*author wzb<wzb@lib-x.com>
*/
class Api_GetBpGsm extends PhalApi_Api
{

	public function getRules()
	{

		return array(
			'hello'=>array(
				'a'=>array('name' =>'a' ,'require'=>true),
				'b'=>array('name' => 'b'),
				),
			'get_bp'=>array(
				'a'=>array('name' =>'a' ,'require'=>true),
				'b'=>array('name' => 'b'),
				),
			);
	}

	public function hello()
	{
		$rs=array();
		$rs['a']=$this->a;
		$rs['b']=$this->b;
		$rs['试下中文']='乱码吗?';
		return $rs;
	}

	public function get_bg()
	{

		$rs=array('code'=> 0,'value'=>array(),'info'=>'');
		$domain=new Domain_GetBpGsm();
		$bg=$domain->get_all();

		if(empty($bg)){
			$rs['info']="no data";
			return $rs;
		}

		$rs['value']=$bg;

		return $rs;
	}
}
