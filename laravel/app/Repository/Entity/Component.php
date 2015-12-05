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
    public static $coefficients = null;

    /** @field * */
    private $sepa_id;
    /** @field * */
    private $name;
    /** @field * */
    private $unit;

    public static function initializeCoefficients()
    {
        if (!isset(self::$coefficients)) {
            self::$coefficients = [
                Station::TYPE_TRAFFIC => [
                    self::COMPONENT_NO2 => [50 => 25 / 50, 100 => 25 / 50, 200 => 25 / 100, 400 => 25 / 200],
                    self::COMPONENT_CO => [5000 => 25 / 5000, 7500 => 25 / 2500, 10000 => 25 / 2500, 20000 => 25 / 10000],
                    self::COMPONENT_PM2P5 => [
                        'hourly' => [15 => 25 / 15, 30 => 25 / 15, 55 => 25 / 25, 110 => 25 / 55],
                        'daily' => [10 => 25 / 10, 20 => 25 / 10, 30 => 25 / 10, 60 => 25 / 30]
                    ],
                    self::COMPONENT_PM10 => [
                        'hourly' => [25 => 25 / 25, 50 => 25 / 25, 90 => 25 / 40, 180 => 25 / 90],
                        'daily' => [15 => 25 / 15, 30 => 25 / 15, 50 => 25 / 20, 100 => 25 / 50]
                    ]
                ],
                Station::TYPE_BACKGROUND => [
                    self::COMPONENT_NO2 => [50 => 25 / 50, 100 => 25 / 50, 200 => 25 / 100, 400 => 25 / 200],
                    self::COMPONENT_CO => [5000 => 25 / 5000, 7500 => 25 / 2500, 10000 => 25 / 2500, 20000 => 25 / 10000],
                    self::COMPONENT_PM2P5 => [
                        'hourly' => [15 => 25 / 15, 30 => 25 / 15, 55 => 25 / 25, 110 => 25 / 55],
                        'daily' => [10 => 25 / 10, 20 => 25 / 10, 30 => 25 / 10, 60 => 25 / 30]
                    ],
                    self::COMPONENT_PM10 => [
                        'hourly' => [25 => 25 / 25, 50 => 25 / 25, 90 => 25 / 40, 180 => 25 / 90],
                        'daily' => [15 => 25 / 15, 30 => 25 / 15, 50 => 25 / 20, 100 => 25 / 50]
                    ],
                    self::COMPONENT_O3 => [60 => 25 / 60, 120 => 25 / 60, 180 => 25 / 60, 240 => 25 / 60],
                    self::COMPONENT_SO2 => [50 => 25 / 50, 100 => 25 / 50, 350 => 25 / 250, 500 => 25 / 150]
                ]

            ];
        }
    }

    public function __construct($dto = [])
    {
        parent::__construct($dto);
        self::initializeCoefficients();
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
        return intval($this->sepa_id);
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