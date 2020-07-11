<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/8
 * Time: 12:20 上午
 */

namespace app\front\module\interceptor;

use Lonicera\core\InterceptorInterface;

class LoginInterceptor implements InterceptorInterface
{
    public function preHandle()
    {
        echo 'LoginInterceptor..preHandle()';
    }

    public function postHandle()
    {
        return true;
    }
}