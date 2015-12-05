<?php
namespace App\Repository\Utils;


class SortablePageableDto implements \JsonSerializable
{

    private $page = 1;
    private $perPage = 10;
    private $orderBy = ['id' => 'asc'];

    private $total = 0;
    private $list = [];

    public function __construct($page, $perPage, $orderBy = ['id' => 'asc'])
    {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->orderBy = $orderBy;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * @return array
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param array $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param array $list
     * @return $this
     */
    public function setList($list)
    {
        $this->list = $list;
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'page' => $this->page,
            'perPage' => $this->perPage,
            'total' => $this->total,
            'list' => $this->getList()
        ];
    }
}