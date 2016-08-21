<?php
class Domain_Geocode
{
	public function regeo($location)
	{
		$model=new Model_Geocode();
		$rs=$model->regeo($location);
		return $rs;
	}
	
}
