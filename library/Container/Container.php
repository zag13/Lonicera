<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/9
 * Time: 11:08 下午
 */


namespace library\Container;


class Container extends ContainerAccess implements ContainerInterface
{
    protected $inject = [];             //存放给bean注入的方法

    protected $instance = [];           //对象存储的数组

    public function get($bean)
    {
        return $this->offsetGet($bean);
    }

    public function has($bean)
    {
        return $this->offsetExists($bean);
    }

    public function set($bean, $value)
    {
        $this->offsetSet($bean, $value);
    }

    //给容器管理的 bean 注入某个方法
    public function inject($bean, $methodName, $methodBody)
    {
        if (!isset($this->inject[$bean][$methodName])) {
            $this->inject[$bean][$methodName] = $methodBody;
        }
    }

    //获取某个 bean 上的方法
    public function getInjectMethod($bean, $methodName)
    {
        if (isset($this->inject[$bean][$methodName])) {
            return $this->inject[$bean][$methodName];
        }
    }

    //TODO 没看懂
    public function callback($bean, $callback, $parameters = [])
    {
        $methods = get_class_methods($this->get($bean));
        if (in_array($callback, $methods, true)) {
            return call_user_func_array([$this->get($bean), $callback], $parameters);
        } else {
            $injectMethod = $this->getInjectMethod($bean, $callback);
            if (isset($injectMethod)) {
                return call_user_func($injectMethod, $parameters);
            } else {
                throw new \RuntimeException('%s not exist %s function', $bean, $callback);
            }
        }
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($bean, $value)
    {
        $this->offsetSet($bean, $value);
    }

    //根据类名来创建对象     laravel简化版
    public function build($className)
    {
        if (is_string($className) && isset($this->instance[$className])) {
            return $this->instance[$className];
        }

        $reflector = new \ReflectionClass($className);
        if (!$reflector->isInstantiable()) {
            throw new \Exception("Can't instantiate " . $className);
        }
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $className;
        }
        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);
        $class = $reflector->newInstanceArgs($dependencies);
        $this->instance[$className] = $class;
        return $class;
    }

    //Todo getClass方法
    public function getDependencies(array $parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if (is_null($dependency)) {
                $dependencies[] = $this->resolveNonClass($parameter);
            } else {
                $dependencies[] = $this->build($dependency->name);
            }
        }
        return $dependencies;
    }

    public function resolveNonClass(\ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        throw new \Exception($parameter->getName() . 'must be not null');
    }

    public function _autoload($path)
    {
        spl_autoload_register(function (string $class) use ($path) {
            $file = DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            if (is_file($path . $file)) {
                include($path . $file);
                return true;
            }
            return false;
        });
    }

}