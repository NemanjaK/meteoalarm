<?php

namespace App\Repository\Entity;


class AlertQueueItem extends Entity
{

    /** @field * */
    private $id;
    /** @field * */
    private $subscriber_id;
    /** @field * */
    private $measurement_id;
    /** @field * */
    private $message;
    /** @field * */
    private $notified;
    /** @field * */
    private $date_created;
    /** @field * */
    private $date_updated;

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
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * @param mixed $date_created
     */
    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated()
    {
        return $this->date_updated;
    }

    /**
     * @param mixed $date_updated
     */
    public function setDateUpdated($date_updated)
    {
        $this->date_updated = $date_updated;
    }


}