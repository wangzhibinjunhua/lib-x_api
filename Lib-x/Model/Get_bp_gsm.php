<?php
class Model_Welcome extends PhalApi_Model_NotORM
{

	public function get_all()
	{
		$sql='select * from gsm_bp';
		return $this->getORM()
			->queryAll($sql);
		//return $this->getORM()
			//->select('*')
			//->fetchAll();

	}

	protected function getTableName($id)
	{
		return 'bg';
	}

}
