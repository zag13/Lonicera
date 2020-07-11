<?php

namespace Lonicera;


use Lonicera\core\BaseException;
use Lonicera\core\Route;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/1
 * Time: 10:40 下午
 */
class Lonicera
{
    private $route;

    public function run()
    {
//        require_once _SYS_PATH . 'core/Loader.php';
//        spl_autoload_register(['Lonicera\core\Loader', 'loadLibClass']);

        if ('debug' == $GLOBALS['_config']['mode']) {
            if (substr(PHP_VERSION, 0, 3) >= "5.5") {
                error_reporting(E_ALL);
            } else {
                error_reporting(E_ALL | E_STRICT);
            }
            set_error_handler(['Lonicera\Lonicera', 'errorHandler']);
            set_exception_handler(['Lonicera\Lonicera', 'exceptionHandler']);
        }

//        require_once _SYS_PATH . 'core/Route.php';
        $capsule = new Capsule();
        $config = $GLOBALS['_config']['db'];

        $capsule->addConnection([
            'driver' => $config['db'],
            'host' => $config['host'],
            'database' => $config['dbname'],
            'username' => $config['username'],
            'password' => $config['password'],
            'charset' => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix' => 'tp_',
        ]);
        // 使用设置静态变量方法，令当前的 Capsule 实例全局可用
        $capsule->setAsGlobal();

        // 启动 Eloquent ORM
        $capsule->bootEloquent();

        $this->route();
        $this->dispatch();
    }

    public function route()
    {
        $this->route = new Route();
        $this->route->init();
    }

    public function dispatch()
    {
        $group = $this->route->group;
        $controlName = $this->route->controller . 'Controller';
        $actionName = $this->route->action . 'Action';
        $className = "app\\{$group}\module\controller\\{$controlName}";


//        $path = _APP . $group . DIRECTORY_SEPARATOR . 'module';
//        $path .= DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $controlName . '.php';
//        require_once $path;

        $methods = get_class_methods($className);
        if (!in_array($actionName, $methods, true)) {
            throw new \Exception(sprintf('方法名$s->$s不存在或非public', $controlName, $actionName));
        }

//        $handler = new $controlName();
//        $handler->param = $this->param;
//        $handler->{$actionName}();
        //todo 通过依赖注入获取route的参数
        $handler = new $className();

        $this->handleInterceptor('preHandle');
        if (in_array('_before_', $methods)) {
            call_user_func([$handler, '_before_']);
        }

//        require_once _SYS_PATH . 'core/Controller.php';
        $reflectedClass = new \ReflectionClass('Lonicera\core\Controller');
        $reflectedProperty = $reflectedClass->getProperty('route');
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($this->route);

        $handler->{$actionName}();
        if (in_array('_after_', $methods)) {
            call_user_func([$handler, '_after_']);
        }
        $this->handleInterceptor('postHandle');
    }

    public function handleInterceptor($type)
    {
        $interceptorArr = $GLOBALS['_config']['interceptorArr'];
        //后置方法需要反向调用
        if($type == 'postHandle'){
            $interceptorArr = array_reverse($interceptorArr);
        }
        $path = "{$this->route->group}/{$this->route->controller}/{$this->route->action}";
        foreach ($interceptorArr as $key=>$value) {
            if ('*' == $value || preg_match($value, $path) > 0) {
                $interceptor = new $key;
                $interceptor->{$type}();
            }
        }
    }

    public static function exceptionHandler($exception)
    {
        if ($exception instanceof BaseException) {
            $exception->errorMessage();
        } else {
            $newException = new BaseException('未知异常', 2000, $exception);
            $newException->errorMessage();
        }
//        echo "caught exception:", $exception->getMessage(), PHP_EOL;
    }

    public static function errorHandler($errNo, $errStr, $errFile, $errLine)
    {
        $err = '错误级别：' . $errNo . '|错误描述：' . $errStr;
        $err .= '|错误所在文件：' . $errFile . '|错误所在行号：' . $errLine . "\r\n";
        echo $err;
        file_put_contents(_APP . 'log.txt', $err, FILE_APPEND);
    }

    private function db()
    {
        $capsule = new Capsule();
        $config = $GLOBALS['_config']['db'];

        $capsule->addConnection([
            'driver' => $config['db'],
            'host' => $config['host'],
            'database' => $config['dbname'],
            'username' => $config['username'],
            'password' => $config['password'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        // 使用设置静态变量方法，令当前的 Capsule 实例全局可用
        $capsule->setAsGlobal();

        // 启动 Eloquent ORM
        $capsule->bootEloquent();
    }
}
