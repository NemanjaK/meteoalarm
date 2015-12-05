<?php

namespace App\Repository;

/**
 * Class StationRepository
 * @package App\Repository
 */
class StationRepository extends AbstractRepository
{

    private static $tableName = "station";
    /** @var StationRepository|null */
    private static $instance = null;

    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @return \App\Repository\StationRepository|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new StationRepository();
        }

        return self::$instance;
    }

    public function getTableName()
    {
        return self::$tableName;
    }

    public function getEntityClass()
    {
        return "\\App\\Repository\\Entity\\Station";
    }
}