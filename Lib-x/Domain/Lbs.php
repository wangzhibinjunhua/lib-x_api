<?php

class Domain_Lbs
{
	public function get_data($cl,$wl)
	{
		$model=new Model_Lbs();
		$rs=$model->get_data($cl,$wl);
		return $rs;
	}

}
