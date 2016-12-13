<?php

/**
* @author wzb<wangzhibin_x@foxmail.com>
* @date Aug 31, 2016 10:59:07 AM
* 儿童手表的所有接口服务
*/

//sign
//DI()->filter = 'PhalApi_Filter_SimpleMD5';
Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
//###########################
class Api_HaWatch extends PhalApi_Api
{

	//增加一些配置定义

	//通信协议 手表厂商名称
	const CS_NAME='HA*';

	//操作手表的api版本
	//const API_VERSION=1.0;
	const API_VERSION=2.0;
	const SOCKET_CLIENT_ID='10101011';
	//cmd
	const API_IS_ONLINE=1001;
	const API_SET_UPLODE_MODE=1002;
	const API_MONITOR=1003;
	const API_SET_SOS_NUMBER=1004;
	const API_RESET=1005;
	const API_REBOOT=1006;
	const API_REQ_LOCATION=1007;
	const API_SHUTDOWN=1008;
	const API_FIND_DEV=1009;
	const API_SET_ALARM=1100;
	const API_SET_CONTACT_A=1101;
	const API_SET_CONTACT_B=1102;
	const API_SEND_MSG=1103;
	const API_ADD_HONOR=1104;
	const API_CLEAR_HONOR=1105;
	const API_SET_SILENCE=1106;
	const API_REMOTE_PHOTO=1107;

	public function getRules()
	{
		return array(

				//通用接口
				'*'=>array(
						'imei'=>array('name'=>'imei','min'=>15,'max'=>15,'require'=>true),
				),
				'get_day_location'=>array(
						'date'=>array('name' =>'date' ,'type'=>'date','require'=>true),
				),
				'set_upload_mode'=>array(
						'upmode'=>array('name' =>'upmode','require'=>true),
				),
				'monitor'=>array(
						'phonenumber'=>array('name' =>'phonenumber','require'=>true),
				),
				'set_sos_number'=>array(
						'sos'=>array('name' =>'sos','require'=>true),
				),
				'set_alarm'=>array(
						'alarm'=>array('name' =>'alarm','require'=>true),
				),
				'set_contact_a'=>array(
						'contacta'=>array('name' =>'contacta','require'=>true),
				),
				'set_contact_b'=>array(
						'contactb'=>array('name' =>'contactb','require'=>true),
				),
				'send_msg'=>array(
						'message'=>array('name' =>'message','require'=>true),
				),
				'set_silence'=>array(
						'silence'=>array('name' =>'silence','require'=>true),
				),
				'get_new_message_list'=>array(
						'user_id'=>array('name' =>'user_id','require'=>true),
				),
				'ignore_message'=>array(
						'user_id'=>array('name' =>'user_id','require'=>true),
						'filename'=>array('name' =>'filename','require'=>true),
				),
				'bind_watch'=>array(
						'user_id'=>array('name' =>'user_id','require'=>true),
				),
				'unbind_watch'=>array(
						'user_id'=>array('name' =>'user_id','require'=>true),
				),
				'get_health_data'=>array(
						'list'=>array('name'=>'list','type'=>'int','min'=>0,'max'=>15,'require'=>true),
						'type'=>array('name'=>'type','type'=>'int','min'=>0,'max'=>2,'require'=>true),
				),

		);

	}




	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Nov 16, 2016 10:01:28 AM
	* 获取心率数据
	* @param imei,手表imei
	* @param list,条数,0代表最近的20条,1代表前20条,依次类推
	* @param type,数据类型,0代表hr,1代表bp,2代表ecg
	* @return 对应的数据和时间
	*/
	public function get_health_data()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		$model=new Model_HaWatch();
		$rs=$model->get_health_data($this->imei,$this->type,$this->list);

		$code=$rs['code']+1000;$success=true;$msg='';
		if($code==1000){
			$success=true;
		}else{
			$success=false;
			$msg=$rs['message'].' '.$rs['info'];
		}
		Common_Lib::report('HaWatch','get_health_data',$success,$code,$msg );
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 22, 2016 4:59:20 PM
	* 解绑手表
	*/

	public function unbind_watch()
	{
		$model=new Model_HaWatch();
		$list=$model->unbind_watch($this->imei,$this->user_id);
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(empty($list)) $rs['code']=1;

		$code=$rs['code']+1000;$success=true;$msg='';
		if($code==1000){
			$success=true;
		}else{
			$success=false;
			$msg=$rs['message'].' '.$rs['info'];
		}
		Common_Lib::report('HaWatch','unbind_watch',$success,$code,$msg );
		return $rs;
	}


	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 22, 2016 4:50:55 PM
	* 绑定手表
	*/
	public function bind_watch()
	{
		$model=new Model_HaWatch();
		$list=$model->bind_watch($this->imei,$this->user_id);
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(empty($list)) $rs['code']=1;
		return $rs;
	}


	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 22, 2016 2:49:41 PM
	* 标记信息为已读
	*/
	public function ignore_message()
	{
		$model=new Model_HaWatch();
		$list=$model->ignore_message($this->imei,$this->user_id,$this->filename);
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 22, 2016 11:38:25 AM
	* 获取新消息列表
	*/
	public function get_new_message_list()
	{
		$model=new Model_HaWatch();
		$list=$model->get_new_message_list($this->imei,$this->user_id);
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(empty($list)){
			$rs['code']=1;
		}else{
			$rs['message']=$list;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Oct 20, 2016 3:26:34 PM
	* 控制手表拍照并上传服务器
	*/

	public function remote_photo()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*PHOTO';
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 2:26:36 PM
	* 设置免打扰时间段
	* silence 时间段,时间段2,时间段3.以,隔开,最多三组
	* 21:10-07:30,09:00-12:00
	*/
	public function set_silence()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*SILENCETIME,'.$this->silence;
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;

	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 2:24:18 PM
	* 清除小红花
	*/
	public function clear_honor()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*HONOR,0';
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 2:23:21 PM
	* 增加小红花
	*/
	public function add_honor()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*HONOR,1';
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 2:17:34 PM
	* 发送文本消息
	* message 为unicode码
	*/
	public function send_msg()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*MESSAGE,'.$this->message;
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 2:12:35 PM
	* 设置电话本后五个
	* contact参数为 联系人,号码,联系人2,号码2.
	* 联系人为unicode码,号码为数字
	*/
	public function set_contact_b()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*PHB2,'.$this->contactb;
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 1:59:31 PM
	* 设置电话本前五个
	*/
	public function set_contact_a()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*PHB,'.$this->contacta;
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 1:54:21 PM
	* 设置闹钟
	*/
	public function set_alarm()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*REMIND,'.$this->alarm;
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 1:50:37 PM
	* 找手表指令
	*/
	public function find_dev()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*FIND';
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;

	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 1:49:26 PM
	* 关机指令
	*/
	public function shutdown()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*POWEROFF';
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}


	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 1:44:54 PM
	* 请求定位
	*/
	public function req_location()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*CR';
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 1:43:16 PM
	* 重启设备
	*/
	public function reboot()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*RESET';
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 1, 2016 1:38:50 PM
	* 恢复出厂设置
	*/
	public function reset()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*FACTORY';
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Aug 31, 2016 6:00:15 PM
	* 设置sos号码
	*/
	public function set_sos_number()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*SOS,'.$this->sos;
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Aug 31, 2016 5:54:01 PM
	* 监听
	*/
	public function monitor()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$data=self::CS_NAME.$this->imei.'*MONITOR,'.$this->phonenumber;
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
			$rs['code']=0;
		}else{
			$rs['code']=1;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Aug 31, 2016 3:56:45 PM
	* 设置数据上传间隔(定位模式1分钟,10分钟,1个小时)
	*/
	public function set_upload_mode()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		//检验间隔时间合法
		if($this->upmode== '1' || $this->upmode== '10' || $this->upmode== '60'){

			if(Common_GatewayClient::isUidOnline($this->imei)){
				$data=self::CS_NAME.$this->imei.'*UPLOAD,'.($this->upmode)*60;
				Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
				Common_GatewayClient::sendToUid($this->imei, Common_GatewayPack::pack_data($data));
				$rs['code']=0;
			}else{
				$rs['code']=1;
			}

		}else{
			$rs['code']=2;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Aug 31, 2016 3:42:40 PM
	* 获取手表在线状态
	*/
	public function is_online()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');

		if(self::API_VERSION === 2.0){
			$r=self::socket_client($this->imei, self::API_IS_ONLINE);
			if($r==1){
				return array('code'=> 0,'message'=>'1','info'=>'online');
			}else{

				return array('code'=> 0,'message'=>'0','info'=>'offline');
			}
		}else {
			Common_GatewayClient::$registerAddress = '127.0.0.1:1330';
			if(Common_GatewayClient::isUidOnline($this->imei)){
				$rs['message']=1;
			}else{
				$rs['message']=0;
			}
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Dec 9, 2016 5:15:42 PM
	* socket 访问
	*/
	public static function socket_client($imei,$info,$addr='tcp://120.76.47.120:10002')
	{
		$client=stream_socket_client($addr);
		if(!$client) return false;
		$msg=array('id'=>self::SOCKET_CLIENT_ID,'cmd'=>'API','imei'=>$imei,'info'=>$info);
		fwrite($client, Common_GatewayPack::pack_data(json_encode($msg)));
		stream_set_timeout($client,10);
		$r=fread($client,8);
		fclose($client);
		return $r;


	}


	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Aug 31, 2016 3:02:32 PM
	* 获取某一天的所有定位坐标
	*/
	public function get_day_location()
	{
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		//检验日期数据是否合法
		if(strtotime( date('Y-m-d', strtotime($this->date)) ) === strtotime( $this->date)){
			$model=new Model_HaWatch();
			$rs=$model->get_day_location($this->imei,$this->date);
			return $rs;
		}else{
			$rs['code']=2;
		}

		$code=$rs['code']+1000;$success=true;$msg='';
		if($code==1000){
			$success=true;
		}else{
			$success=false;
			$msg=$rs['message'].' '.$rs['info'];
		}
		Common_Lib::report('HaWatch','get_day_location',$success,$code,$msg );
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Aug 31, 2016 11:23:25 AM
	* 获取最新的一次定位位置
	*/
	public function get_lastest_location(){

		$rs=array('code'=> 0,'message'=>'','info'=>'');
		$domain=new Domain_HaWatch();
		$rs=$domain->get_lastest_location($this->imei);

// 		if(empty($lastest_location))
// 		{
// 			$rs['code']=1;
// 		}else{
// 			$rs['code']=0;
// 			$rs['message']=$lastest_location;
// 		}

	$code=$rs['code']+1000;$success=true;$msg='';
	if($code==1000){
		$success=true;
	}else{
		$success=false;
		$msg=$rs['message'].' '.$rs['info'];
	}
	Common_Lib::report('HaWatch','get_lastest_location',$success,$code,$msg );
		return $rs;
	}


}
