<?php
namespace App\Infrastructure;


class PDOTransactional extends \PDO
{

    private $transactionCounter = 0;

    public function beginTransaction()
    {
        $result = null;
        if ($this->transactionCounter === 0) {
            $result = parent::beginTransaction();
        }
        $currentTransaction = $this->transactionCounter++;
        return isset($result) ? $result : ($currentTransaction !== 0);
    }

    public function commit()
    {
        $result = null;
        if ($this->transactionCounter === 1) {
            $result = parent::commit();
        }

        $currentTransaction = $this->transactionCounter--;
        return isset($result) ? $result : $currentTransaction !== 0;
    }

    public function rollBack()
    {
        $result = null;
        if ($this->transactionCounter === 1) {
            $result = parent::rollBack();
        }

        $currentTransaction = $this->transactionCounter--;
        return isset($result) ? $result : $currentTransaction !== 0;
    }

}