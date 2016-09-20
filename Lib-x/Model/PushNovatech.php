<?php
class Model_PushNovatech extends PhalApi_Model_NotORM
{

	public function polling($id)
	{

		$sql='select * from push_novatech where id > :id order by id desc limit 0 ,1';
		$params=array(':id'=> $id);
		return $this->getORM('android_push')
			->queryAll($sql,$params);

	}

	protected function getTableName($id)
	{
		return 'novatech';
	}

}
