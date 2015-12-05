<?php

namespace App\Repository\Entity;


class Component extends Entity
{

    const COMPONENT_SO2 = 1;
    const COMPONENT_PM10 = 5;
    const COMPONENT_PM2P5 = 6001;
    const COMPONENT_NO2 = 8;
    const COMPONENT_CO = 10;
    const COMPONENT_O3 = 7;


    // Coefficients used for CAQI calculation
    private static $coefficients = [
        Station::TYPE_TRAFFIC => [
            self::COMPONENT_NO2 => [25 / 50, 25 / 50, 25 / 100, 25 / 200],
            self::COMPONENT_CO => [25 / 5000, 25 / 2500, 25 / 2500, 25 / 10000],
            self::COMPONENT_PM2P5 => [
                'hourly' => [25 / 15, 25 / 15, 25 / 25, 25 / 55],
                'daily' => [25 / 10, 25 / 10, 25 / 10, 25 / 30]
            ],
            self::COMPONENT_PM10 => [
                'hourly' => [25 / 25, 25 / 25, 25 / 40, 25 / 90],
                'daily' => [25 / 15, 25 / 15, 25 / 20, 25 / 50]
            ]
        ],
        Station::TYPE_BACKGROUND => [
            self::COMPONENT_NO2 => [25 / 50, 25 / 50, 25 / 100, 25 / 200],
            self::COMPONENT_CO => [25 / 5000, 25 / 2500, 25 / 2500, 25 / 10000],
            self::COMPONENT_PM2P5 => [
                'hourly' => [25 / 15, 25 / 15, 25 / 25, 25 / 55],
                'daily' => [25 / 10, 25 / 10, 25 / 10, 25 / 30]
            ],
            self::COMPONENT_PM10 => [
                'hourly' => [25 / 25, 25 / 25, 25 / 40, 25 / 90],
                'daily' => [25 / 15, 25 / 15, 25 / 20, 25 / 50]
            ],
            self::COMPONENT_O3 => [25 / 60, 25 / 60, 25 / 60, 25 / 60],
            self::COMPONENT_SO2 => [25 / 50, 25 / 50, 25 / 250, 25 / 150]
        ],

    ];

    /** @field * */
    private $sepa_id;
    /** @field * */
    private $name;
    /** @field * */
    private $unit;


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
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param mixed $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }


}