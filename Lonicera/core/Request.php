<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/8
 * Time: 12:37 上午
 */


namespace Lonicera\core;


class Request
{
    public function getParam($param)
    {
        if (isset($_REQUEST[$param])) {
            return $_REQUEST[$param];
        } else {
            return null;
        }
    }

    public function getInt($param)
    {
        return intval($this->getParam($param));
    }
}