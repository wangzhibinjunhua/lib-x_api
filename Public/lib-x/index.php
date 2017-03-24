<?php
/**
 * Lib-x 统一入口
 */

require_once dirname(__FILE__) . '/../init.php';

//装载你的接口
DI()->loader->addDirs('Lib-x');

//注册一些服务

//DI()->filter = 'Common_LibxFilter';
/** ---------------- 响应接口请求 ---------------- **/

$api = new PhalApi();
$rs = $api->response();
$rs->output();

