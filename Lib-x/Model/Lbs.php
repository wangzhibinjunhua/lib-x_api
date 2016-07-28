<?php
class Model_Lbs extends PhalApi_Model_NotORM
{

	public function get_data($cl,$wl)
	{

		$url='http://api.cellocation.com/loc/?'.'cl='.$cl.'&wl='.$wl.'$output=json';
		$client = Common_HttpClient::create()
        ->withHost($url);
        $rs = $client->reset()
	    ->withTimeout(3000)
	    ->request();

	    return $rs;

	}

	protected function getTableName($id)
	{
		//return 'novatech';
	}


}
