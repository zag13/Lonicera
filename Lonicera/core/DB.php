<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/3
 * Time: 9:31 下午
 */

namespace Lonicera\core;

//TODO 缺陷：一直内嵌$instance    不完善  未进行参数绑定导致未真正实现save操作

class DB
{
    private $dbLink;
    protected $queryNum = 0;
    private static $instance;
    protected $PDOStatement;
    //事务数
    protected $transTimes = 0;
    protected $bind = [];
    public $rows = 0;

    private function __construct($config)
    {
        $this->connect($config);
    }

    //todo 单例模式  有缺陷会一直嵌套 感觉是PHPstorm的原因
    public static function getInstance($config)
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function connect($config)
    {
        try {
            $dsn = "{$config['db']}:host={$config['host']};dbname={$config['dbname']}";
            $this->dbLink = new \PDO($dsn, $config['username'], $config['password'], $config['param']);
        } catch (\PDOException $e) {
            throw new BaseException("数据库连接失败", 1000, $e);   //此异常无法处理，记录日志后往上抛
        }
        return $this->dbLink;
    }

    public function query($sql, $bind = [], $fetchType = \PDO::FETCH_ASSOC)
    {
        if (!$this->dbLink) {
            return new \Exception('数据库连接失败');
        }
        $this->PDOStatement = $this->dbLink->prepare($sql);
        $this->execute($sql, $bind);
        $ret = $this->PDOStatement->fetchAll($fetchType);
        $this->rows = count($ret);
        return $ret;
    }

    public function execute($sql, $bind = [])
    {
        if (!$this->dbLink) {
            throw new \Exception('数据库连接失败');
        }
        $this->PDOStatement = $this->dbLink->prepare($sql);
        $ret = $this->PDOStatement->execute($bind);
        $this->rows = $this->PDOStatement->rowCount();
        return $ret;
    }

    public function startTrans()
    {
        ++$this->transTimes;
        if (1 == $this->transTimes) {
            $this->dbLink->beginTransaction();
        } else {
            $this->dbLink->execute("SAVEPOINT tr{$this->transTimes}");
        }
    }

    public function commit()
    {
        if (1 == $this->transTimes) {
            $this->dbLink->commit();
        }
        --$this->transTimes;
    }

    public function rollback()
    {
        if (1 == $this->transTimes) {
            $this->dbLink->rollback();
        } elseif ($this->transTimes > 1) {
            $this->dbLink->execute("ROLLBACK TO SAVEPOINT tr{$this->transTimes}");
        }
        $this->transTimes = max(0, $this->transTimes - 1);
    }
}