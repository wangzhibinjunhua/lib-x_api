<?php
class Common_LibxFilter implements PhalApi_Filter
{
	public function check()
	{
		$public_key=DI()->request->get('key');
		if($public_key != 'huayingtek'){
			 throw new PhalApi_Exception_BadRequest('wrong key', 1);
		}

	}


}
