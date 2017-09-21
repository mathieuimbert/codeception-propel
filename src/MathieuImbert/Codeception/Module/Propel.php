<?php
namespace MathieuImbert\Codeception\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Propel\Runtime\ActiveQuery\ModelCriteria;
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
        $this->connection = \Propel\Runtime\Propel::getConnection($this->config['connection']);
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
     * @param string $entity
     * @param array $data
     */
    public function haveRecord($entity, array $data)
    {
        // TODO
    }

    /**
     * @param string $entity
     * @param array $data
     */
    public function seeRecord($entity, array $data)
    {
        $record = $this->grabRecord($entity, $data);
        $this->assertNotNull($record);
    }

    /**
     * @param string $entity
     * @param array $data
     */
    public function dontSeeRecord($entity, array $data)
    {
        $record = $this->grabRecord($entity, $data);
        $this->assertNull($record);
    }

    /**
     * @param $entity
     * @param array $data
     *
     * @return object|null
     */
    public function grabRecord($entity, array $data)
    {
        // Table map class
        $tableMap = $entity::TABLE_MAP;
        $tableName = $tableMap::TABLE_NAME;

        // Query class name
        $queryClass = $entity . 'Query';

        // Create a query object
        $query = $queryClass::create();

        foreach ($data as $key => $value) {
            $query->where("$tableName.$key" . ' = ?', $value);
        }

        return $query->findOne();
    }
}
