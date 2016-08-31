<?php

class Common_GatewayPack
{
	public static function pack_data($data)
	{
		$data_len=sprintf("%04x",strlen($data));
		return $data_len.$data;
	}
}