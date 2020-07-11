<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/9
 * Time: 11:01 下午
 */


namespace library\Container;


class ContainerAccess implements \ArrayAccess
{
    private $keys = [];

    public function __construct(array $values = [])
    {
    }

    public function offsetExists($offset)
    {
        return isset($this->keys[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->keys[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->keys[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if(isset($this->keys[$offset])){
            unset($this->keys[$offset]);
        }
    }
}