<?php

namespace EzPlatform\ActivitiesLog\Repository\Services;

use eZ\Publish\API\Repository\UserService;
use EzPlatform\ActivitiesLog\Repository\Value\InteractiveLoginData;

/**
 * Class ActivitiesLogInteractiveLoginService.
 */
class ActivitiesLogInteractiveLoginService
{
    private ActivitiesLogRepositoryService $activitiesLogRepositoryService;
    private UserService $userService;

    /**
     * ActivitiesLogInteractiveLoginService constructor.
     */
    public function __construct(
        ActivitiesLogRepositoryService $activitiesLogRepositoryService,
        UserService $userService
    ) {
        $this->activitiesLogRepositoryService = $activitiesLogRepositoryService;
        $this->userService                    = $userService;
    }

    /**
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function getInteractiveLoginData($userId): ?InteractiveLoginData
    {
        $userInteractiveLogin = $this->activitiesLogRepositoryService->getUserInteractiveLogin($userId);

        if (0 === \count($userInteractiveLogin)) {
            return null;
        }

        $passwordInfo = $this->userService->getPasswordInfo($this->userService->loadUser($userId));

        return new InteractiveLoginData(
            [
                'loginNumber' => \count($userInteractiveLogin),
                'lastLogin' => $userInteractiveLogin[0],
                'passwordExpired' => $passwordInfo->isPasswordExpired(),
                'passwordExpirationDate' => $passwordInfo->getExpirationDate() ? $passwordInfo->getExpirationDate()->diff(new \DateTime()) : null,
            ]
        );
    }
}
