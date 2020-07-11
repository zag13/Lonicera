<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/6
 * Time: 11:50 下午
 */

namespace Lonicera\core;


class Loader
{
    public static function loadClass()
    {

    }

    public static function loadLibClass($class)
    {
//        var_dump($class);
        $classFile = _ROOT . $class . '.php';
        $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $classFile);
//        echo $classFile;
        require_once $classFile;
    }
}