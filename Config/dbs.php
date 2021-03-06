<?php
/**
 * 分库分表的自定义数据库路由配置
 */

/**
 * @Author   wzb<wangzhibin_x@qq.com>
 * @DateTime 2016-07-09T10:47:24+0800
 * 数据库配置文件
 * 启用新的配置文件,此文件为模版参考
 *
 */
return array(
    /**
     * DB数据库服务器集群
     */
    'servers' => array(

        //gsm bpbp db
        'db_lib-x_health' => array(                         //服务器标记
            'host'      => 'localhost',             //数据库域名
            'name'      => 'health',               //数据库名字
            'user'      => 'root',                  //数据库用户名
            'password'  => 'password',                       //数据库密码
            'port'      => '3306',                  //数据库端口
            'charset'   => 'UTF8',                  //数据库字符集
        ),
    ),

    /**
     * 自定义路由表
     */
    'tables' => array(
        //通用路由
        '__default__' => array(
            'prefix' => 'gsm_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_lib-x_health'),
            ),
        ),


        /**
        'demo' => array(                                                //表名
            'prefix' => 'lib-x_',                                         //表名前缀
            'key' => 'id',                                              //表主键名
            'map' => array(                                             //表路由配置
                array('db' => 'db_lib-x'),                               //单表配置：array('db' => 服务器标记)
                array('start' => 0, 'end' => 2, 'db' => 'db_lib-x'),     //分表配置：array('start' => 开始下标, 'end' => 结束下标, 'db' => 服务器标记)
            ),
        ),
         */
    ),
);
