<?php

namespace EzPlatform\ActivitiesLog\Repository;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class User.
 */
class User
{
    /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface */
    private $tokenStorage;

    /**
     * User constructor.
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
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

        $userId = $token->getUser()->getAPIUser()->contentInfo->id;

        return $userId;
    }
}
