<?php
class Domain_HaWatch
{
	
	public function get_lastest_location($imei)
	{
		$model=new Model_HaWatch();
		$rs=$model->get_lastest_location($imei);
		return $rs;
	}
}