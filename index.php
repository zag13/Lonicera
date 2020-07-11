<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/1
 * Time: 10:32 下午
 */

define('_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);       //网站根目录
define('_SYS_PATH', _ROOT . 'Lonicera' . DIRECTORY_SEPARATOR);       //系统目录
define('_APP',_ROOT . 'app' .DIRECTORY_SEPARATOR);
define('_VERSION','0.1');
$GLOBALS['_config'] = require _SYS_PATH . 'config.php';             //省去在文件里面命名
//require _SYS_PATH.'config.php';
require _ROOT . 'vendor/autoload.php';
require _SYS_PATH.'Lonicera.php';
$app = new Lonicera\Lonicera();
$app->run();