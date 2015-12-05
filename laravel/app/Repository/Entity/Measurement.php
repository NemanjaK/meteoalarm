<?php

namespace App\Repository\Entity;


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

    public function __construct($dto = [])
    {
        parent::__construct($dto);
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


}