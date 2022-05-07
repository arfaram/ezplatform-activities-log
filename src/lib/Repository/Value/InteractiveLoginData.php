<?php

declare(strict_types=1);

namespace EzPlatform\ActivitiesLog\Repository\Value;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

class InteractiveLoginData extends ValueObject
{
    /** @var int|null */
    public $loginNumber;

    /** @var \EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog|null */
    public $lastLogin;

    /** @var bool|null */
    public $passwordExpired;

    /** @var \DateInterval|null */
    public $passwordExpirationDate;
}
