<?php
namespace App\Repository;


use App\Infrastructure\DBConnection;
use App\Repository\Entity\Entity;
use App\Repository\Exceptions\QueryException;
use App\Repository\Utils\SortablePageableDto;

abstract class AbstractRepository
{

    /** @var  \PDO */
    protected $conn;
    /** @var \FluentPDO */
    protected $queryBuilder;

    protected function __construct()
    {
        // Acquire connection
        $this->conn = DBConnection::getConnection();
        $this->queryBuilder = new \FluentPDO($this->conn);
    }

    /**
     * Method returns entity object for provided ID
     *
     * @param $id
     *
     * @return Entity|null
     */
    public function getById($id)
    {
        $dto = $this->queryBuilder->from($this->getTableName(), intval($id))->fetch();
        $entityClass = $this->getEntityClass();

        return !empty($dto) ? new $entityClass($dto) : null;
    }

    public function countAll()
    {
        $total = $this->queryBuilder->from($this->getTableName())->count();
        return $total;
    }

    public function getAllWithDto(SortablePageableDto $dto, $statusFilter = [], $termFilter = null)
    {
        $query = $this->queryBuilder->from($this->getTableName());

        if (!empty($statusFilter)) {
            $query->where($this->getTableName() . '.status', $statusFilter);
        }

        if (isset($termFilter) && !empty($termFilter)) {
            $query->innerJoin('charter ON charter.idcharter = ' . $this->getTableName() . '.charter_id');
            $query->innerJoin('user ON user.id = ' . $this->getTableName() . '.user_id');
            $query->leftJoin('customer_profile ON customer_profile.user_id = ' . $this->getTableName() . '.user_id');
            $query->leftJoin('captain_profile ON captain_profile.user_id = ' . $this->getTableName() . '.user_id');
            $whereConcat = "CONCAT_WS('|',CAST(booking.id AS CHAR), CONVERT(charter.title USING utf8), customer_profile.first_name, customer_profile.last_name, booking.customer_name, booking.trip_date, user.email, captain_profile.first_name, captain_profile.last_name)";
            $query->where($whereConcat . ' LIKE "%' . $termFilter . '%";');

        }

        if (isset($dto)) {
            $count = $query->count();
            $dto->setTotal($count);
            $query->limit($dto->getPerPage());
            $offset = $dto->getPerPage() * ($dto->getPage() - 1);
            $query->offset($offset);
            $orderBy = $dto->getOrderBy();
            foreach ($orderBy as $col => $dir) {
                $query->orderBy($col . " " . $dir);
            }
        }

        $dtoList = $query->fetchAll();
        $result = [];
        $entityClass = $this->getEntityClass();
        foreach ($dtoList as $item) {
            $result[] = new $entityClass($item);
        }

        return isset($dto) ? $dto->setList($result) : $result;
    }

    public function getAll($limit = null, $offset = null, $orderBy = null)
    {
        $query = $this->queryBuilder->from($this->getTableName());
        if (isset($limit)) {
            $query->limit($limit);
        }

        if (isset($offset)) {
            $query->offset($offset);
        }

        if (isset($orderBy)) {
            $query->orderBy($orderBy);
        }

        $dtos = $query->fetchAll();
        $entityClass = $this->getEntityClass();
        $result = [];

        foreach ($dtos as $dto) {
            $result[$dto['id']] = new $entityClass($dto);
        }

        return $result;
    }

    abstract public function getTableName();

    abstract public function getEntityClass();

    /**
     * Method used to save entity into it's own database
     *
     * @param Entity $entity
     *
     * @return bool|int|\PDOStatement
     * @throws QueryException
     */
    public function save(Entity $entity)
    {
        $dto = $entity->getDto();
        $query = null;
        if (intval($entity->getId()) > 0 && !$entity->isForceInsert()) {
            unset($dto['id']); // No need to "update" ID field
            $query = $this->queryBuilder->update($this->getTableName(), $dto, $entity->getId());
        } else {
            $query = $this->queryBuilder->insertInto($this->getTableName(), $dto);
        }
        try {
            $result = $query->execute();
        } catch (\PDOException $ex) {
            throw new QueryException($ex, $query);
        }

        return $result;
    }

    public function delete(Entity $entity)
    {
        $query = $this->queryBuilder->delete($this->getTableName(), $entity->getId());
        try {
            $result = $query->execute();
        } catch (\PDOException $ex) {
            throw new QueryException($ex, $query);
        }

        return $result;
    }

    protected function startDebug()
    {
        $this->queryBuilder->debug = function (\CommonQuery $fluentQuery) {
            var_dump($fluentQuery->getQuery());
            die();
        };
    }

}
