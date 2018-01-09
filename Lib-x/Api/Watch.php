<?php

/**
* @author wzb<wangzhibin_x@foxmail.com>
* @date Aug 31, 2016 10:59:07 AM
* 儿童手表的所有接口服务
*/

//sign
DI()->filter = 'PhalApi_Filter_SimpleMD5';
Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
//###########################
class Api_Watch extends PhalApi_Api
{
	
	//增加一些配置定义
	
	//通信协议 手表厂商名称
	const CS_NAME='CS*';
	
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

		);

	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 22, 2016 4:59:20 PM
	* 解绑手表
	*/

	public function unbind_watch()
	{
		$model=new Model_Watch();
		$list=$model->unbind_watch($this->imei,$this->user_id);
		$rs=array('code'=> 0,'message'=>'','info'=>'');
		if(empty($list)) $rs['code']=1;

		return $rs;
	}


	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Sep 22, 2016 4:50:55 PM
	* 绑定手表
	*/
	public function bind_watch()
	{
		$model=new Model_Watch();
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
		$model=new Model_Watch();
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
		$model=new Model_Watch();
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
			Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
				Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
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
		Common_GatewayClient::$registerAddress = '127.0.0.1:1238';
		if(Common_GatewayClient::isUidOnline($this->imei)){
			$rs['message']=1;
		}else{
			$rs['message']=0;
		}
		return $rs;
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
			$model=new Model_Watch();
			$day_location=$model->get_day_location($this->imei,$this->date);
			if(empty($day_location)){
				$rs['code']=1;
			}else{
				$rs['message']=$day_location;
			}
		}else{
			$rs['code']=2;
		}
		return $rs;
	}

	/**
	* @author wzb<wangzhibin_x@foxmail.com>
	* @date Aug 31, 2016 11:23:25 AM
	* 获取最新的一次定位位置
	*/
	public function get_lastest_location(){

		$rs=array('code'=> 0,'message'=>'','info'=>'');
		$domain=new Domain_Watch();
		$lastest_location=$domain->get_lastest_location($this->imei);

		if(empty($lastest_location))
		{
			$rs['code']=1;
		}else{
			$rs['code']=0;
			$rs['message']=$lastest_location;
		}
		return $rs;
	}


}
