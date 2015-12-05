<?php

namespace App\Repository;

use App\Repository\Entity\Subscriber;

/**
 * Class SubscriberRepository
 * @package App\Repository
 */
class SubscriberRepository extends AbstractRepository
{

    private static $tableName = "subscriber";
    /** @var SubscriberRepository|null */
    private static $instance = null;

    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @return \App\Repository\SubscriberRepository|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new SubscriberRepository();
        }

        return self::$instance;
    }

    public function getByUuid($uuid) {
        $query = $this->queryBuilder->from(self::$tableName);
        $query->where('uuid', $uuid);
        $query->limit(1);
        $dto = $query->fetch();
        return !empty($dto) ? new Subscriber($dto) : null;
    }

    public function getTableName()
    {
        return self::$tableName;
    }

    public function getEntityClass()
    {
        return "\\App\\Repository\\Entity\\Subscriber";
    }
}