<?php
/**
 * PhalApi_Response_Json JSON响应类
 *
 * @package     PhalApi\Response
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2015-02-09
 */

class PhalApi_Response_Json extends PhalApi_Response {

    public function __construct() {
    	$this->addHeaders('Content-Type', 'application/json;charset=utf-8');
    }

    protected function formatResult($result) {
        //return json_encode($result);
        //return json_encode($result,JSON_UNESCAPED_UNICODE);//modify by wzb 20160707
        return self::json_encode_ex($result);
    }

    protected function json_encode_ex($value){
        if (version_compare(PHP_VERSION,'5.4.0','<'))
        {
            $str = json_encode($value);
            $str = preg_replace_callback(
                                    "#\\\u([0-9a-f]{4})#i",
                                    function($matchs)
                                    {
                                         return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
                                    },
                                     $str
                                    );
            return $str;
        }
        else
        {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
}

}
