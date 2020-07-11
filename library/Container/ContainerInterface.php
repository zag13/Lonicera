<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/9
 * Time: 11:06 下午
 */


namespace library\Container;


interface ContainerInterface
{
    public function get($bean);

    public function has($bean);

    public function set($bean, $value);
}