<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/8
 * Time: 12:16 上午
 */


namespace Lonicera\core;


interface InterceptorInterface
{
    public function preHandle();

    public function postHandle();
}