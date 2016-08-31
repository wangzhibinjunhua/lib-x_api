<?php
class Domain_Watch
{
	
	public function get_lastest_location($imei)
	{
		$model=new Model_Watch();
		$rs=$model->get_lastest_location($imei);
		return $rs;
	}
}