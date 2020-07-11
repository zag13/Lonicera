<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/6
 * Time: 10:20 下午
 */

namespace library\render;

//require_once _SYS_PATH . 'core/Render.php';

use Lonicera\core\Render;

class PhpRender implements Render
{
    private $value = array();

    public function init()
    {

    }

    public function assign($key, $value)
    {
        $this->value[$key] = $value;
    }

    public function display($view = '')
    {
       extract($this->value);       //取出变量，导入视图函数
       include $view;
    }
}