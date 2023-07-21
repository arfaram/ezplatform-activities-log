<?php

namespace EzPlatform\ActivitiesLog\Repository;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class User.
 */
class User
{
    private TokenStorageInterface $tokenStorage;

    /**
     * User constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }

    public function getCurrentUserId()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token || !\is_object($token->getUser())) {
            return;
        }

        return $token->getUser()->getAPIUser()->contentInfo->id;
    }
}
