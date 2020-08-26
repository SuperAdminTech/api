<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Action\GivePermissionsAction;
use App\Entity\Account;
use App\Entity\Permission;
use App\Entity\User;
use App\Security\Restricted;
use Symfony\Component\Serializer\Annotation\Groups;


class PermissionWithUsername implements Restricted {

    /**
     * @var Account
     * @Groups({"user:write"})
     */
    public $account;

    /**
     * @var string
     * @Groups({"user:write"})
     */
    public $username;

    /**
     * @var array
     * @Groups({"user:write"})
     */
    public $grants;

    /**
     * @inheritDoc
     */
    function allowsRead(User $user): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    function allowsWrite(User $user): bool
    {
        foreach ($user->permissions as $permission){
            if ($permission->account->id == $this->account->id && in_array(Permission::ACCOUNT_MANAGER, $permission->grants))
                return true;
        }
        return false;
    }
}
