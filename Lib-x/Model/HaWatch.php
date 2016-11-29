<?php
class Model_HaWatch extends PhalApi_Model_NotORM {
	public function get_health_data($imei, $type, $list) {
		$type = intval ( $type );
		$list = intval ( $list );

		switch ($type) {
			case 0 : // hr
				$where ['hr>?'] = 0;
				$r = $this->getORM ( 'ha_watch', 'health_data' )->select ( 'hr,create_time' )->where ( $where )->order ( 'id DESC' )->limit ( $list * 20, 20 )->fetchAll ();
				if ($r) {
					return array (
							'code' => 0,
							'message' => $r,
							'info' => ''
					);
				}
				break;
			case 1 : // bp
				$where ['bph>?'] = 0;
				$where ['bph>?'] = 0;
				$r = $this->getORM ( 'ha_watch', 'health_data' )->select ( 'bph,bpl,create_time' )->where ( $where )->order ( 'id DESC' )->limit ( $list * 20, 20 )->fetchAll ();
				if ($r) {
					return array (
							'code' => 0,
							'message' => $r,
							'info' => ''
					);
				}
				break;
			case 2 : // ecg
				$where ['ecg>?'] = 0;
				$r = $this->getORM ( 'ha_watch', 'health_data' )->select ( 'ecg,create_time' )->where ( $where )->order ( 'id DESC' )->limit ( $list * 20, 20 )->fetchAll ();
				if ($r) {
					return array (
							'code' => 0,
							'message' => $r,
							'info' => ''
					);
				}
				break;
			default :
				break;
		}
		return array (
				'code' => 1,
				'message' => '无数据',
				'info' => ''
		);
	}
	public function get_lastest_location($imei) {
		// $sql='select watch_time,location_lon,location_lat,location_type,location_content,battery from watch_info where imei = :imei order by id desc limit 0 ,1';
		$where ['location_type>=?'] = 0;
		$where ['imei'] = $imei;
		$r = $this->getORM ( 'ha_watch', 'info' )
				  ->select ( 'watch_time,location_lon,location_lat,location_type,location_content,battery' )
				  ->where ( $where )
		          ->order ( 'id DESC' )
		          ->limit ( 0, 1 )
		          ->fetchAll ();
		if ($r) {
			return array (
					'code' => 0,
					'message' => $r,
					'info' => ''
			);
		} else {
			return array (
					'code' => 1,
					'message' => "无数据",
					'info' => ''
			);
		}
	}
	public function get_day_location($imei, $date) {
		// $sql='select location_type,location_lon,location_lat from watch_info where imei = :imei and date(watch_time) = :datetime';
		$where ['location_type>=?'] = 0;
		$where ['imei'] = $imei;
		$where ['date(watch_time)'] = $date;
		$r = $this->getORM ( 'ha_watch', 'info' )->select ( 'watch_time,location_lon,location_lat,location_type,location_content,battery' )->where ( $where )->order ( 'id DESC' )->fetchAll ();
		if ($r) {
			return array (
					'code' => 0,
					'message' => $r,
					'info' => ''
			);
		} else {
			return array (
					'code' => 1,
					'message' => "无数据",
					'info' => ''
			);
		}
	}
	public function get_new_message_list($imei, $user_id) {
		$sql = 'select file from watch_message where flag=0 and user_id=:user_id and imei=:imei';
		$params = array (
				':user_id' => $user_id,
				':imei' => $imei
		);
		return $this->getORM ( 'ha_watch' )->queryAll ( $sql, $params );
	}
	public function ignore_message($imei, $user_id, $filename) {
		$sql = 'update watch_message set flag=:flag where imei=:imei and user_id=:user_id and file=:filename';
		$params = array (
				':flag' => '1',
				':imei' => $imei,
				':user_id' => $user_id,
				':filename' => $filename
		);
		return $this->getORM ( 'ha_watch' )->queryAll ( $sql, $params );
	}
	public function bind_watch($imei, $user_id) {
		$sql = 'select * from watch_app_watch where watch_imei=:imei and app_id=:id';
		$params = array (
				':imei' => $imei,
				':id' => $user_id
		);
		$test = $this->getORM ( 'ha_watch' )->queryAll ( $sql, $params );
		if (! empty ( $test )) {
			return null;
		}

		$data = array (
				'watch_imei' => $imei,
				'app_id' => $user_id
		);
		$rs = $this->getORM ( 'ha_watch' )->insert ( $data );
		return $rs;
		// return $this->getORM('ha_watch')
		// ->insert($data);
	}
	public function unbind_watch($imei, $user_id) {
		return $this->getORM ( 'ha_watch' )->where ( 'watch_imei', $imei )->where ( 'app_id', $user_id )->delete ();
	}
	protected function getTableName($id) {
		return 'app_watch';
	}
}
