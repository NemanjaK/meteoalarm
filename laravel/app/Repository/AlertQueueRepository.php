<?php

namespace App\Repository;

/**
 * Class AlertQueueRepository
 * @package App\Repository
 */
class AlertQueueRepository extends AbstractRepository
{

    private static $tableName = "alert_queue";

    /** @var AlertQueueRepository|null */
    private static $instance = null;

    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @return \App\Repository\AlertQueueRepository|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new AlertQueueRepository();
        }

        return self::$instance;
    }

    public function getTableName()
    {
        return self::$tableName;
    }

    public function getEntityClass()
    {
        return "\\App\\Repository\\Entity\\AlertQueueItem";
    }
}