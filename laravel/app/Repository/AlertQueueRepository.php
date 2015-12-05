<?php

namespace App\Repository;

use App\Repository\Entity\AlertQueueItem;
use App\Repository\Entity\Subscriber;

/**
 * Class AlertQueueRepository
 * @package App\Repository
 */
class AlertQueueRepository extends AbstractRepository
{

    private static $tableName = "alert_queue";

    /** @var AlertQueueRepository|null */
    private static $instance = null;

    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @return \App\Repository\AlertQueueRepository|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new AlertQueueRepository();
        }

        return self::$instance;
    }

    /**
     * @param \App\Repository\Entity\Subscriber $subscriber
     *
     * @return array
     */
    public function getForSubscriber(Subscriber $subscriber)
    {
        $query = $this->queryBuilder->from(self::$tableName);
        $query->where('subscriber_id', $subscriber->getId());
        $query->where('notified', 0);
        $dtos = $query->fetchAll();
        $result = [];
        foreach ($dtos as $dto) {
            $result[] = new AlertQueueItem($dto);
        }

        return $result;
    }

    public function getTableName()
    {
        return self::$tableName;
    }

    public function getEntityClass()
    {
        return "\\App\\Repository\\Entity\\AlertQueueItem";
    }
}