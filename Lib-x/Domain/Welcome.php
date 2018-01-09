<?php

class Domain_Welcome
{
	public function get_all()
	{
		$model=new Model_Welcome();
		$rs=$model->get_all();
		return $rs;
	}

}
