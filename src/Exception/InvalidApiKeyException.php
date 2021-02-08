<?php


namespace App\Exception;


use ccxt\ExchangeError;

class InvalidApiKeyException extends InvalidDataException {

    public static function createFromExchangeError(ExchangeError $e)
    {
        $needle = 'bitmex ';
        if (str_starts_with($e->getMessage(), $needle)) {
            $bitmexException = json_decode(substr($e->getMessage(), strlen($needle)));
            return new self("BitMEX: {$bitmexException->error->message}");
        }
        return new self($e->getMessage());
    }
}