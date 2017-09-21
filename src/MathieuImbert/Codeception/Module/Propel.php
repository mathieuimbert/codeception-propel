<?php
namespace MathieuImbert\Codeception\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Propel\Runtime\Connection\ConnectionInterface;


class Propel extends Module
{

    /** @var ConnectionInterface $connection */
    protected $connection;

    protected $config = [
        'cleanup' => true,
        'connection' => null
    ];

    public function _beforeSuite($settings = [])
    {
        $this->connection = \Propel\Runtime\Propel::getConnection($settings['']);
    }

    public function _before(TestInterface $test)
    {
        if ($this->config['cleanup']) {
            $this->connection->beginTransaction();
        }
    }

    public function _after(TestInterface $test)
    {
        if ($this->config['cleanup'] && $this->connection->inTransaction()) {
            $this->connection->rollback();
        }
    }

    /**
     * @param string $model
     * @param array $data
     */
    public function haveInDatabase($model, array $data)
    {
        $map = \Propel\Runtime\Propel::getDatabaseMap();
        var_dump($map);
        exit;
    }
}
