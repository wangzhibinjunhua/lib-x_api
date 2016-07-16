<?php
class Model_PushNovatech extends PhalApi_Model_NotORM
{

	public function polling()
	{

		$sql='select * from push_novatech order by id desc limit 0 ,1';
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
