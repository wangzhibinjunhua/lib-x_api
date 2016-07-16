<?php
class Model_PushNovatech extends PhalApi_Model_NotORM
{

	public function polling($id)
	{

		$sql='select * from push_novatech where id > '.$id.' order by id desc limit 0 ,1';
		//DI()->logger->debug("wzb","model:".$sql);
		return $this->getORM('android_push')
			->queryAll($sql);
		//return $this->getORM()
			//->select('*')
			//->fetchAll();

	}

	protected function getTableName($id)
	{
		return 'novatech';
	}

}
