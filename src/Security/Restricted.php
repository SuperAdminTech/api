<?php


namespace App\Security;


use App\Entity\User;

interface Restricted {

    /**
     * @param User $user
     * @return bool
     */
    function allowsRead(User $user): bool;

    /**
     * @param User $user
     * @return bool
     */
    function allowsWrite(User $user): bool;
}