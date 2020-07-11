<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/1
 * Time: 10:53 下午
 */

namespace Lonicera\core;

//传统形式：index.php?c=index&a=read&id=1&name=Fan
//PATH_INFO：index.php/index/read/id/1/name/Fan
class Route
{
    public $group;         //分组名，或者为module
    public $controller;
    public $action;
    public $params;

    public function __construct()
    {
    }

    public function init()
    {
        $route = $this->getRequest();
        $this->group = $route['group'];
        $this->controller = $route['controller'];
        $this->action = $route['action'];
        !empty($route['param']) && $this->params = $route['param'];
    }

    /**
     * 可以对不同样式的url进行分类处理，目前只处理了Path Url方式和传统URL方式的解析
     * Date: 2020/7/1  Time: 11:47 下午
     */
    public function getRequest()
    {
        $filter_param = array('<', '>', '"', "'", '%3C', '%3E', '%22', '%27', '%3c', '%3e');
        $uri = str_replace($filter_param, '', $_SERVER['REQUEST_URI']);
        $path = parse_url($uri);
        if (strpos($path['path'], 'index.php') === false) {
            // 不存在 index.php
            $urlR0 = $path['path'];
        } else {
            $urlR0 = substr($path['path'], strlen('index.php') + 1);
        }
        $urlR = ltrim($urlR0, '/');
        if ($urlR == '') {
            $route = $this->parseTradition();
            return $route;
        } else {
            $route = $this->parsePathInfo($urlR, $path);
            return $route;
        }
    }

    /**
     * 解析传统的URL
     * Date: 2020/7/1  Time: 11:49 下午
     */
    public function parseTradition()
    {
        $route = [];
        if (empty($_GET[$GLOBALS['_config']['UrlGroupName']])) {
            $_GET[$GLOBALS['_config']['UrlGroupName']] = '';
        }
        if (empty($_GET[$GLOBALS['_config']['UrlControllerName']])) {
            $_GET[$GLOBALS['_config']['UrlControllerName']] = '';
        }
        if (empty($_GET[$GLOBALS['_config']['UrlActionName']])) {
            $_GET[$GLOBALS['_config']['UrlActionName']] = '';
        }
        $route['group'] = $_GET[$GLOBALS['_config']['UrlGroupName']];
        $route['controller'] = $_GET[$GLOBALS['_config']['UrlControllerName']];
        $route['action'] = $_GET[$GLOBALS['_config']['UrlActionName']];
        unset($_GET[$GLOBALS['_config']['UrlGroupName']]);
        unset($_GET[$GLOBALS['_config']['UrlControllerName']]);
        unset($_GET[$GLOBALS['_config']['UrlActionName']]);
        $route['param'] = $_GET;
        if ($route['group'] == null) {
            $route['group'] = $GLOBALS['_config']['defaultApp'];
        }
        if ($route['controller'] == null) {
            $route['controller'] = $GLOBALS['_config']['defaultController'];
        }
        if ($route['action'] == null) {
            $route['action'] = $GLOBALS['_config']['defaultAction'];
        }
        return $route;
    }

    /**
     * 解析PathInfo格式的路由
     * Date: 2020/7/2  Time: 10:05 下午
     */
    public function parsePathInfo($urlR = null, $path = null)
    {
        $reqArr = explode('/', $urlR);
        foreach ($reqArr as $key => $value) {
            if (empty($value)) unset($reqArr[$key]);
        }
        $cnt = count($reqArr);
        if (empty($reqArr) || empty($reqArr[0])) {
            $cnt = 0;
        }
        switch ($cnt) {
            case 0:
                $route['group'] = $GLOBALS['_config']['defaultApp'];
                $route['controller'] = $GLOBALS['_config']['defaultController'];
                $route['action'] = $GLOBALS['_config']['defaultAction'];
                break;
            case 1:
                if (stripos($reqArr[0], ':')) {
                    $gc = explode(':', $reqArr[0]);
                    $route['group'] = $gc[0];
                    $route['controller'] = $gc[1];
                    $route['action'] = $GLOBALS['_config']['defaultAction'];
                } else {
                    $route['group'] = $GLOBALS['_config']['defultApp'];
                    $route['controller'] = $reqArr[0];
                    $route['action'] = $GLOBALS['_config']['defaultAction'];
                }
                break;
            default:
                if (stripos($reqArr[0], ':')) {
                    $gc = explode(':', $reqArr[0]);
                    $route['group'] = $gc[0];
                    $route['controller'] = $gc[1];
                    $route['action'] = $reqArr[1];
                } else {
                    $route['group'] = $GLOBALS['_config']['defaultApp'];
                    $route['controller'] = $reqArr[0];
                    $route['action'] = $reqArr[1];
                }
                for ($i = 2; $i < $cnt; $i++) {
                    $route['param'][$reqArr[$i]] = isset($reqArr[++$i]) ? $reqArr[$i] : '';
                }
                break;
        }
        if (!empty($path['query'])) {
            parse_str($path['query'], $routeQ);
            if (empty($route['param'])) {
                $route['param'] = array();
            }
            $route['param'] += $routeQ;
        }
        return $route;
    }
}