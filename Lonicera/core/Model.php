<?php

/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/3
 * Time: 11:41 下午
 */

namespace Lonicera\core;

class Model
{
    //自动生成PO(persistant object)持久对象
    public function buildPO($tableName, $prefix = '')
    {
        $db = DB::getInstance($GLOBALS['_config']['db']);
        $ret = $db->query('SELECT * FROM `tp_one` WHERE `user` = "张三"',
            array('TABLENAME' => $this->getRealTableName($tableName, $prefix)));
        $className = ucfirst($tableName);
        $file = _APP . 'model/' . $className . '.php';

        $classString = "<?php \r\nclass $className extends Model { \r\n ";
        foreach ($ret as $key => $value) {
            $classString .= 'public $' . "{$value['COLUMN_NAME']};";
            if (!empty($value['COLUMN_NAME'])) {
                $classString .= "              // {$value['COLUMN_NAME']}";
            }
            $classString .= "\r\n";
        }
        $classString .= "}";
        file_put_contents($file, $classString);
    }

    //使用反射获取了PO对象所持有的属性和属性值
    public function save()
    {
        $reflect = new \ReflectionClass($this);
        // 是获取 PUBLIC 字段
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $keyArray = array_column($props, 'name');
        $keys = implode(',', $keyArray);
        $prepareKeys = implode(',', array_map(function ($key) {
            return ':' . $key;
        }, $keyArray));

        $sqlTemplate = 'insert into ' . $this->getTableNameByPO($reflect) . "({$keys}) values ({$prepareKeys})";
        $data = [];
        foreach ($props as $v) {
            $data[$v->name] = $reflect->getProperty($v->name)->getValue($this);
        }
//        require_once _SYS_PATH . 'core/DB.php';

        $db = DB::getInstance($GLOBALS['_config']['db']);
        $result = $db->query($sqlTemplate, $data);

        return $result;
    }


    public function getRealTableName($tableName, $prefix = '')
    {
        if (!empty($prefix)) {
            $realTableName = $prefix . "{$tableName}";
        } elseif (isset($GLOBALS['_config']['db']['prefix']) && !empty($GLOBALS['_config']['db']['prefix'])) {
            $realTableName = $GLOBALS['_config']['db']['prefix'] . "_{$tableName}";
        } else {
            $realTableName = $tableName;
        }
        return $realTableName;
    }

    //从类名生成表名，暂不考虑多个单词下的驼峰规则需要考虑命名空间的问题
    public function getTableNameByPO($reflect)
    {
        return $this->getRealTableName(strtolower($reflect->getShortName()));
    }
}