<?php

namespace App\Repository;

use App\Repository\Entity\Station;

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

    public function findBySepaId($sepaId)
    {
        $query = $this->queryBuilder->from(self::$tableName);
        $query->where('sepa_id', $sepaId);
        $query->limit(1);
        $dto = $query->fetch();

        return !empty($dto) ? new Station($dto) : null;
    }

    /**
     * @param $lat
     * @param $lng
     *
     * @return array
     */
    public function getCaqiForLocation($lat, $lng)
    {
        $earthRadius = 6731;
        for ($radius = 5; $radius < 100; $radius += 5) {
            $maxLatitude = doubleval($lat) + rad2deg($radius / $earthRadius);
            $minLatitude = doubleval($lat) - rad2deg($radius / $earthRadius);
            $maxLongitude = $lng + rad2deg($radius / $earthRadius / cos(deg2rad($lat)));
            $minLongitude = $lng - rad2deg($radius / $earthRadius / cos(deg2rad($lat)));

            $query = "SELECT firstCut.*,
                            acos(sin(:lat)*sin(radians(latitude)) + cos(:lat)*cos(radians(latitude))*cos(radians(longitude)-:lon)) * :R As D
                FROM (
                    Select *
                    From `station`
                    WHERE latitude BETWEEN :minLat AND :maxLat
                      AND longitude BETWEEN :minLon AND :maxLon
                ) AS firstCut
            WHERE acos(sin(:lat)*sin(radians(latitude)) + cos(:lat)*cos(radians(latitude))*cos(radians(longitude)-:lon)) * :R < :rad
              AND firstCut.aqi_value > 0
            ORDER BY D LIMIT 1";

            $params = array(
                'lat' => deg2rad($lat),
                'lon' => deg2rad($lng),
                'minLat' => $minLatitude,
                'minLon' => $minLongitude,
                'maxLat' => $maxLatitude,
                'maxLon' => $maxLongitude,
                'rad' => $radius,
                'R' => $earthRadius,
            );

            $dto = $this->queryBuilder->getPdo()->prepare($query);
            $dto->execute($params);
            $dto = $dto->fetch();
            if (empty($dto)) {
                continue;
            }
            return new Station($dto);
        }

        return null;
    }
}