<?php

class Domain_PushNovatech
{
	public function polling($id)
	{
		$model=new Model_PushNovatech();
		$id=intval($id);
		$rs=$model->polling($id);
		return $rs;
	}

}
