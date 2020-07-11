<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/8
 * Time: 12:23 上午
 */


namespace app\front\module\interceptor;


use Lonicera\core\InterceptorInterface;

class PayInterceptor implements InterceptorInterface
{
    public function preHandle()
    {
        echo 'PayInterceptor..preHandle()';
    }

    public function postHandle()
    {
        return true;
    }

}