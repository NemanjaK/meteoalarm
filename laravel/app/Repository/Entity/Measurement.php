<?php

namespace App\Repository\Entity;


use App\Repository\ComponentRepository;
use App\Repository\StationRepository;

class Measurement extends Entity
{
    /** @field * */
    private $station_id;
    /** @field * */
    private $component_id;
    /** @field * */
    private $measure_timestamp;
    /** @field * */
    private $alert;
    /** @field * */
    private $value;

    /** @var Component */
    private $component;
    /** @var \App\Repository\ComponentRepository|null */
    private $componentRepository;

    /** @var Station */
    private $station;
    /** @var \App\Repository\StationRepository|null */
    private $stationRepository;

    public function __construct($dto = [])
    {
        parent::__construct($dto);
        $this->componentRepository = ComponentRepository::getInstance();
        $this->stationRepository = StationRepository::getInstance();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getComponentId()
    {
        return $this->component_id;
    }

    /**
     * @param mixed $component_id
     */
    public function setComponentId($component_id)
    {
        $this->component_id = $component_id;
    }

    /**
     * @return mixed
     */
    public function getMeasureTimestamp()
    {
        return $this->measure_timestamp;
    }

    /**
     * @param mixed $measure_timestamp
     */
    public function setMeasureTimestamp($measure_timestamp)
    {
        $this->measure_timestamp = $measure_timestamp;
    }

    /**
     * @return mixed
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * @param mixed $alert
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getStation()
    {
        if (empty($this->station)) {
            /** @var Station $station */
            $station = $this->componentRepository->getById($this->getStationId());
            $this->setStation($station);
        }
        return $this->station;
    }

    /**
     * @param mixed $station
     */
    public function setStation(Station $station)
    {
        $this->station = $station;
    }

    /**
     * @return Component
     */
    public function getComponent()
    {
        if (empty($this->component)) {
            /** @var Component $component */
            $component = $this->componentRepository->getById($this->getComponentId());
            $this->setComponent($component);
        }
        return $this->component;
    }

    /**
     * @param mixed $component
     */
    public function setComponent(Component $component)
    {
        $this->component = $component;
    }


}