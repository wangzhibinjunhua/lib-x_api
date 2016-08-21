<?php
class Domain_Weather
{
	
	public function get_weather_by_cityname($cityname)
	{
		$model=new Model_Weather();
		$rs=$model->get_weather_by_cityname($cityname);
		return $rs;
	}
}