<?php

namespace App\Repository\Entity;


use App\Repository\StationAqiHistoryRepository;

class StationAqiHistoryItem extends Entity
{
    private static $siepaFields = [
        Component::COMPONENT_NO2 => "no2",
        Component::COMPONENT_CO => "co",
        Component::COMPONENT_PM2P5 => [
            'hourly' => "pm2p5_hourly",
            'daily' => "pm2p5_daily"
        ],
        Component::COMPONENT_PM10 => [
            'hourly' => "pm10_hourly",
            'daily' => "pm10_daily"
        ],
        Component::COMPONENT_O3 => "o3",
        Component::COMPONENT_SO2 => "so2"
    ];


    /** @field * */
    private $station_id;
    /** @field * */
    private $no2;
    /** @field * */
    private $co;
    /** @field * */
    private $pm2p5_hourly;
    /** @field * */
    private $pm2p5_daily;
    /** @field * */
    private $pm10_hourly;
    /** @field * */
    private $pm10_daily;
    /** @field * */
    private $o3;
    /** @field * */
    private $so2;
    /** @field * */
    private $timestamp;


    public function __construct($dto = [])
    {
        parent::__construct($dto);
    }

    protected function initializeRepository()
    {
        $this->repository = StationAqiHistoryRepository::getInstance();
    }

    /**
     * @return mixed
     */
    public function getStationId()
    {
        return $this->station_id;
    }

    /**
     * @param mixed $station_id
     */
    public function setStationId($station_id)
    {
        $this->station_id = $station_id;
    }

    /**
     * @return mixed
     */
    public function getNo2()
    {
        return $this->no2;
    }

    /**
     * @param mixed $no2
     */
    public function setNo2($no2)
    {
        $this->no2 = $no2;
    }

    /**
     * @return mixed
     */
    public function getCo()
    {
        return $this->co;
    }

    /**
     * @param mixed $co
     */
    public function setCo($co)
    {
        $this->co = $co;
    }

    /**
     * @return mixed
     */
    public function getPm2p5Hourly()
    {
        return $this->pm2p5_hourly;
    }

    /**
     * @param mixed $pm2p5_hourly
     */
    public function setPm2p5Hourly($pm2p5_hourly)
    {
        $this->pm2p5_hourly = $pm2p5_hourly;
    }

    /**
     * @return mixed
     */
    public function getPm2p5Daily()
    {
        return $this->pm2p5_daily;
    }

    /**
     * @param mixed $pm2p5_daily
     */
    public function setPm2p5Daily($pm2p5_daily)
    {
        $this->pm2p5_daily = $pm2p5_daily;
    }

    /**
     * @return mixed
     */
    public function getPm10Hourly()
    {
        return $this->pm10_hourly;
    }

    /**
     * @param mixed $pm10_hourly
     */
    public function setPm10Hourly($pm10_hourly)
    {
        $this->pm10_hourly = $pm10_hourly;
    }

    /**
     * @return mixed
     */
    public function getPm10Daily()
    {
        return $this->pm10_daily;
    }

    /**
     * @param mixed $pm10_daily
     */
    public function setPm10Daily($pm10_daily)
    {
        $this->pm10_daily = $pm10_daily;
    }

    /**
     * @return mixed
     */
    public function getO3()
    {
        return $this->o3;
    }

    /**
     * @param mixed $o3
     */
    public function setO3($o3)
    {
        $this->o3 = $o3;
    }

    /**
     * @return mixed
     */
    public function getSo2()
    {
        return $this->so2;
    }

    /**
     * @param mixed $so2
     */
    public function setSo2($so2)
    {
        $this->so2 = $so2;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function setIndex($indexKey, $indexValue, $type = 'hourly')
    {
        if (isset(self::$siepaFields[$indexKey])) {
            if ($indexKey === Component::COMPONENT_PM2P5 || $indexKey === Component::COMPONENT_PM10) {
                $field = self::$siepaFields[$indexKey][$type];
            } else {
                $field = self::$siepaFields[$indexKey];
            }

            $this->$field = $indexValue;
        }
    }


}