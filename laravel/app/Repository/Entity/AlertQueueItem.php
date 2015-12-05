<?php

namespace App\Repository\Entity;


use App\Repository\AlertQueueRepository;

class AlertQueueItem extends Entity
{

    /** @field * */
    private $subscriber_id;
    /** @field * */
    private $measurement_id;
    /** @field * */
    private $message;
    /** @field * */
    private $notified;
    /** @field */
    private $value;

    public function __construct($dto = [])
    {
        parent::__construct($dto);
    }

    protected function initializeRepository()
    {
        $this->repository = AlertQueueRepository::getInstance();
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
    public function getSubscriberId()
    {
        return $this->subscriber_id;
    }

    /**
     * @param mixed $subscriber_id
     */
    public function setSubscriberId($subscriber_id)
    {
        $this->subscriber_id = $subscriber_id;
    }

    /**
     * @return mixed
     */
    public function getMeasurementId()
    {
        return $this->measurement_id;
    }

    /**
     * @param mixed $measurement_id
     */
    public function setMeasurementId($measurement_id)
    {
        $this->measurement_id = $measurement_id;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getNotified()
    {
        return $this->notified;
    }

    /**
     * @param mixed $notified
     */
    public function setNotified($notified)
    {
        $this->notified = $notified;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return doubleval($this->value);
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = doubleval($value);
    }


}