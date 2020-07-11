<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/6
 * Time: 10:16 下午
 */

namespace Lonicera\core;

interface Render
{
    public function init();

    public function assign($key,$value);

    public function display($view='');
}
 