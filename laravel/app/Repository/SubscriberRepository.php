<?php

namespace App\Repository;

/**
 * Class SubscriberRepository
 * @package App\Repository
 */
class SubscriberRepository extends AbstractRepository
{

    private static $tableName = "subscriber";
    /** @var SubscriberRepository|null */
    private static $instance = null;

    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @return \App\Repository\SubscriberRepository|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new SubscriberRepository();
        }

        return self::$instance;
    }

    public function getTableName()
    {
        return self::$tableName;
    }

    public function getEntityClass()
    {
        return "\\App\\Repository\\Entity\\Subscriber";
    }
}