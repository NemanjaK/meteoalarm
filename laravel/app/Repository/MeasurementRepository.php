<?php

namespace App\Repository;

/**
 * Class MeasurementRepository
 * @package App\Repository
 */
class MeasurementRepository extends AbstractRepository
{

    private static $tableName = "measurement";
    /** @var MeasurementRepository|null */
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
            self::$instance = new MeasurementRepository();
        }

        return self::$instance;
    }

    public function getTableName()
    {
        return self::$tableName;
    }

    public function getEntityClass()
    {
        return "\\App\\Repository\\Entity\\Measurement";
    }
}