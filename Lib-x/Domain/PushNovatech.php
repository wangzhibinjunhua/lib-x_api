<?php

class Domain_PushNovatech
{
	public function polling()
	{
		$model=new Model_PushNovatech();
		$rs=$model->polling();
		return $rs;
	}

}
