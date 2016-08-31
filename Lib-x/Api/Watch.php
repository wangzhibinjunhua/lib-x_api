<?php

/**
* @author wzb<wangzhibin_x@foxmail.com>
* @date Aug 31, 2016 10:59:07 AM
* 儿童手表的所有接口服务
*/
class Api_Watch extends PhalApi_Api
{
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
		);

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
				$data='CS*'.$imei.'UPLOAD,'.$this->upmode;
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