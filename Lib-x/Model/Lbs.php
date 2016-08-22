<?php
class Model_Lbs extends PhalApi_Model_NotORM
{

	public function get_data($params)
	{

		//高德基站定位
		$url='http://apilocate.amap.com/position?'.$params;
		//var_export($url);
		$curl=new PhalApi_CUrl();
		$rs=$curl->get($url);
	    return $rs;

	}

	//基站wifi混合定位
	public function bs_wifi()
	{

	}


	//基站定位
	public function bs()
	{


	}

	//wifi定位
	public function wifi()
	{


	}

	protected function getTableName($id)
	{
		//return 'novatech';
	}


}
