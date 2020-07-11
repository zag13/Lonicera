<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/6
 * Time: 10:32 下午
 */

namespace Lonicera\core;

use library\render\PhpRender;

class Controller
{
    private $db;
    private $view;
    protected static $route;
    protected $request;

    public function __construct()
    {
//        require_once _ROOT . 'library/Render/PhpRender.php';
        $this->view = new PhpRender();
        $this->request = new Request();
    }


    /** TODO 不会死循环吗？？？
     * 赋值给模板
     * @param $key
     * @param $val
     * Date: 2020/7/6  Time: 10:58 下午
     * @return PhpRender
     */
    protected function assign($key, $val)
    {
        $this->view->assign($key, $val);
        return $this->view;
    }

    /**
     * 调度DB
     * @param array $conf
     * Date: 2020/7/6  Time: 11:03 下午
     * @return DB
     */
    public function db($conf = array())
    {
        if ($conf == null) {
            $conf = $GLOBALS['_config']['db'];
        }
        $this->db = DB::getInstance($conf);
        return $this->db;
    }

    /**
     * 渲染视图并输出到浏览器中
     * @param string $file
     * Date: 2020/7/6  Time: 11:03 下午
     */
    public function display($file = "")
    {
        if (func_num_args() == 0 || $file == null) {
            $controller = self::$route->controller;
            $action = self::$route->action;
            $viewFilePath = _ROOT . 'app/' . self::$route->group . '/module/view/';
            $viewFilePath .=  $action . '.php';
        } else {
            $viewFilePath = $file . '.php';
        }
        $this->view->display($viewFilePath);
    }
}