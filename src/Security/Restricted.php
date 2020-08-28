<?php


namespace App\Security;

use App\Entity\User;

/**
 * Interface Restricted
 * @package App\Security
 */
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