<?php

class Domain_Lbs
{
	public function get_data($bts,$nearbts,$macs)
	{

		//default params
		$key='key=b36a2c392a659a6a548f069bd3b1be4b';
		$accesstype='accesstype=0';
		$imei='imei=358688000000158';
		$output='output=json';
		$cdma='cdma=0';

		$params=$accesstype.'&'.$imei.'&'.$cdma.'&'.$output.'&'.'bts='.$bts.'&macs='.$macs.'&'.$key;

		$model=new Model_Lbs();
		$rs=$model->get_data($params);
		return $rs;
	}

}
