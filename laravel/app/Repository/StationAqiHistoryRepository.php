<?php

namespace App\Repository;


use App\Repository\Entity\StationAqiHistoryItem;

class StationAqiHistoryRepository extends AbstractRepository
{

    private static $tableName = "station_aqi_history";

    /** @var StationAqiHistoryRepository|null */
    private static $instance = null;

    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \App\Repository\MeasurementRepository|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new StationAqiHistoryRepository();
        }

        return self::$instance;
    }

    public function getTableName()
    {
        return self::$tableName;
    }

    public function getEntityClass()
    {
        return "\\App\\Repository\\Entity\\StationAqiHistoryItem";
    }
}