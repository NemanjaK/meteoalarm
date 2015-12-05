<?php

namespace App\Repository;
use App\Repository\Entity\Component;

/**
 * Class ComponentRepository
 * @package App\Repository
 */
class ComponentRepository extends AbstractRepository
{

    private static $tableName = "component";

    /** @var ComponentRepository|null */
    private static $instance = null;

    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @return \App\Repository\ComponentRepository|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new ComponentRepository();
        }

        return self::$instance;
    }

    public function getTableName()
    {
        return self::$tableName;
    }

    public function getEntityClass()
    {
        return "\\App\\Repository\\Entity\\Component";
    }

    /**
     * @param $sepaId
     *
     * @return \App\Repository\Entity\Component|null
     */
    public function getBySepaId($sepaId)
    {
        $query = $this->queryBuilder->from(self::$tableName);
        $query->where('sepa_id', $sepaId);
        $query->limit(1);
        $dto = $query->fetch();

        return !empty($dto) ? new Component($dto) : null;
    }
}