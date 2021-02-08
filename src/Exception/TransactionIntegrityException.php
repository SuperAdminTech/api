<?php


namespace App\Exception;


class TransactionIntegrityException extends \LogicException {

    /**
     * TransactionIntegrityException constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}