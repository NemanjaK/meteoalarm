<?php

namespace App\Repository;

use App\Repository\Entity\Component;
use App\Repository\Entity\Measurement;
use App\Repository\Entity\Station;

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

    /**
     * @param \App\Repository\Entity\Station   $station
     * @param \App\Repository\Entity\Component $component
     *
     * @return \App\Repository\Entity\Measurement|null
     */
    public function getLatestForStationAndComponent(Station $station, Component $component) {
        $query = $this->queryBuilder->from(self::$tableName);
        $query->where('station_id', $station->getId());
        $query->where('component_id', $component->getId());
        $query->orderBy('measure_timestamp');
        $query->limit(1);
        $dto = $query->fetch();
        return !empty($dto) ? new Measurement($dto) : null;
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