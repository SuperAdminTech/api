<?php


namespace App\Security;


use App\Entity\User;

interface Restricted {

    /**
     * @return User[]
     */
    function getWriters(): array;

    /**
     * @return User[]
     */
    function getReaders(): array;
}