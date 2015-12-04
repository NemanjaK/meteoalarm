<?php
namespace App\Repository\Entity;

use App\Repository\AbstractRepository;

/**
 * Class Entity
 * Abstract class that is extended
 * by all entity classes. It implements
 * DTO <-> Entity conversion and basic
 * CRUD operations.
 * @package App\Persistence
 */
class Entity implements \JsonSerializable
{

    // Keeps reflection data for each entity class
    private static $entityFields = [];
    /** @field */
    protected $id;
    /** @field * */
    protected $date_created;
    /** @field * */
    protected $date_updated;
    // Keeps reflection data for current instance entity class
    protected $fields = [];
    // If ID is specified this is the only way to force "insert" operation.
    private $forceInsert = false;
    /** @var AbstractRepository */
    protected $repository;

    /**
     * Entity constructor.
     *
     * @param array $dto
     */
    protected function __construct($dto = [])
    {
        $this->initializeFields();
        $this->initializeRepository();
        if (!empty($dto)) {
            $this->initializeFromDto($dto);
        }
    }

    protected function initializeRepository()
    {
        $cNames = explode("\\", get_class($this));
        $scName = end($cNames);
        $className = "App\\Repository\\" . $scName . "Repository";
        if (class_exists($className)) {
            $this->repository = $className::getInstance();
        }
    }

    /**
     * Initialization method that populates $entityFields
     * and $fields array.
     */
    private function initializeFields()
    {
        // Initialize fields array
        $className = get_class($this);
        if (!isset(self::$entityFields[$className]) || empty(self::$entityFields[$className]) === true) {
            $reflection = new \ReflectionClass($this);
            /** @var \ReflectionProperty $property */
            foreach ($reflection->getProperties() as $property) {

                $annotation = $property->getDocComment();
                if (strpos($annotation, "@field")) {
                    self::$entityFields[$className][$property->getName()] = $property;
                }
            }
        }

        $this->fields = self::$entityFields[$className];
    }

    /**
     * @param array $dto
     */
    protected function initializeFromDto($dto = [])
    {
        if (!empty($dto) && is_array($dto)) {
            /**
             * @var                     $key
             * @var \ReflectionProperty $property
             */
            foreach ($this->fields as $key => $property) {
                $property->setAccessible(true);
                if (isset($dto[$key])) {
                    $property->setValue($this, $dto[$key]);
                } else {
                    $property->setValue($this, null);
                }
                $property->setAccessible(false);
            }
        }
    }

    /**
     * Converts current entity instance into DTO
     *
     * @return array
     */
    public function getDto()
    {
        $dto = [];
        /**
         * @var                     $key
         * @var \ReflectionProperty $property
         */
        foreach ($this->fields as $key => $property) {
            $property->setAccessible(true);
            $dto[$key] = $property->getValue($this);
            $property->setAccessible(false);
        }

        return $dto;
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

    /**
     * @return boolean
     */
    public function isForceInsert()
    {
        return $this->forceInsert;
    }

    /**
     * @param boolean $forceInsert
     */
    public function setForceInsert($forceInsert)
    {
        $this->forceInsert = $forceInsert;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->getDto();
    }

    public function save()
    {
        $result = $this->repository->save($this);
        if (empty($this->id)) {
            $this->setId($result);
        }
    }
}
