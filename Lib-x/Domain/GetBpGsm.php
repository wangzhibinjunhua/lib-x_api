<?php

class Domain_GetBpGsm
{
	public function get_all()
	{
		$model=new Model_GetBpGsm();
		$rs=$model->get_all();
		return $rs;
	}

}
