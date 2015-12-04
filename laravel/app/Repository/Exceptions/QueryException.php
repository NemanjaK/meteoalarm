<?php
namespace App\Repository\Exceptions;


class QueryException extends \Exception
{

    private $callingClass;
    private $callingMethod;

    public function __construct(\PDOException $pdoEx, \BaseQuery $query)
    {
        $queryString = str_ireplace(array("\n", "\t", PHP_EOL), " ", $query->getQuery());
        parent::__construct("[SQL Error #{$pdoEx->getCode()}] - {$pdoEx->getMessage()}. Failed to execute query" . PHP_EOL . "\t{$queryString}." . PHP_EOL);
    }

}
