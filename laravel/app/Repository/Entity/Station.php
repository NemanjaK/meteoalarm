<?php

namespace App\Repository\Entity;


class Station extends Entity
{

    /** @field */
    private $eoi_code;
    /** @field */
    private $name;
    /** @field */
    private $network;
    /** @field */
    private $type;
    /** @field */
    private $sepa_id;
    /** @field */
    private $started;
    /** @field */
    private $zone;
    /** @field */
    private $city;
    /** @field */
    private $latitude;
    /** @field */
    private $longitude;
    /** @field */
    private $altitude;

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
    public function getEoiCode()
    {
        return $this->eoi_code;
    }

    /**
     * @param mixed $eoi_code
     */
    public function setEoiCode($eoi_code)
    {
        $this->eoi_code = $eoi_code;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param mixed $network
     */
    public function setNetwork($network)
    {
        $this->network = $network;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSepaId()
    {
        return $this->sepa_id;
    }

    /**
     * @param mixed $sepa_id
     */
    public function setSepaId($sepa_id)
    {
        $this->sepa_id = $sepa_id;
    }

    /**
     * @return mixed
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * @param mixed $started
     */
    public function setStarted($started)
    {
        $this->started = $started;
    }

    /**
     * @return mixed
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @param mixed $zone
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * @param mixed $altitude
     */
    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;
    }


}