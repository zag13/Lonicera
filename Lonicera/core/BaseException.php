<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/7
 * Time: 11:23 下午
 */


namespace Lonicera\core;


class BaseException extends \Exception
{
    public function __toString()
    {
        return self::getMessage();
    }

    protected function _Log()
    {
        $err = date('Y-m-d H:i:s') . '|';
        $err .= '异常消息:' . self::getMessage() . '|';
        $err .= '异常码:' . self::getCode() . PHP_EOL;
//        $err .= '堆栈回溯:' . json_encode(debug_backtrace()) . PHP_EOL;
        echo $err;
//        file_put_contents(_APP . 'log.txt', $err, FILE_APPEND);
    }

    public function errorMessage()
    {
        self::_Log();
    }
}