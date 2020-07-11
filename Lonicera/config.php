<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/1
 * Time: 10:53 下午
 */

return [
    'mode' => 'noDebug',                      //应用程序模式
    'filter' => 'true',                     //是否过滤$_GET、$_POST、$_COOKIE、$_FILES
    'charSet' => 'utf-8',                   //网页编码格式
    'defaultApp' => 'front',                //默认的分组
    'defaultController' => 'index',         //默认的控制器名称
    'defaultAction' => 'index',             //默认的动作名称
    'UrlControllerName' => 'c',             //自定义控制器名称，例如：index.php?c=index
    'UrlActionName' => 'a',                 //自定义方法名称，例如：index.php?c=index&a=Index
    'UrlGroupName' => 'g',                  //自定义分组名
    'db' => [
        'db' => 'mysql',
        'host' => '127.0.0.1:8889', // localhost 不行
        'dbname' => 'tp51_temp',
        'prefix' => 'tp_',
        'username' => 'root',
        'password' => 'root',
        'param' => [],
    ],
    'smtp' => [],
    'interceptorArr' => [
        'app\front\module\interceptor\LoginInterceptor' => '*',
        'app\front\module\interceptor\PayInterceptor' => '~front/in(.*)~',
    ],
];
 