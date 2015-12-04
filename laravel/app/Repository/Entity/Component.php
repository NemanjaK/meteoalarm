<?php

namespace App\Repository\Entity;


class Component extends Entity
{
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