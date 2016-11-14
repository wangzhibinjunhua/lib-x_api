<?php
class Common_Lib{
/**
 * 日期格式转换为时间戳
 * 2015年3月9日 下午6:30:08
 * @param string $time 2011-10-23 21:59:21
 * @return 时间戳
 */
public static function toDateUnix($time)
{
	$year = ((int) substr($time, 0, 4)); // 取得年份
	$month = ((int) substr($time, 5, 2)); // 取得月份
	$day = ((int) substr($time, 8, 2)); // 取得几号
	$hour = ((int) substr($time, 11, 2)); //
	$minute = ((int) substr($time, 14, 2)); //
	$second = ((int) substr($time, 17, 2)); //
	// 小时、分、秒、月、天、年
	// int mktime(int hour, int minute, int second, int month, int day, int year, int [is_dst] );
	return mktime($hour, $minute, $second, $month, $day, $year);
}

/**
* @author wzb<wangzhibin_x@foxmail.com>
* @date Oct 27, 2016 7:45:29 PM
* PHP生成唯一的uuid
* @param
* @return uuid字符串
*/
public static function getuuid(){
	for($i=0; $i<10; $i++){
		$rand[] = mt_rand(1, 9999999999);
	}
	$randStr = implode('-', $rand);
	$randStr = time()."-".$randStr;
	$randStr = strtolower(md5($randStr));
	$randStr .= mt_rand(1000, 9999);
	return $randStr;
}

// 删除数组中对应key的元素
public static function delArrayItems($paramArr, $keyStr)
{
	foreach ($paramArr as $k => $v) {
		$e = strrpos(',' . $keyStr . ',', ',' . $k . ',');
		if ($e === false) {} else {
			unset($paramArr[$k]);
		}
	}
	return $paramArr;
}

}