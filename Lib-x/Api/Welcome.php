<?php

/**
*
*/
class Api_Welcome extends PhalApi_Api
{

	public function getRules(){

		return array(
			'hello'=>array(
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
}
