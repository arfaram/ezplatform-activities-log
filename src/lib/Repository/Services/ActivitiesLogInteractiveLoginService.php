<?php

namespace EzPlatform\ActivitiesLog\Repository\Services;

use Ibexa\Contracts\Core\Repository\UserService;
use EzPlatform\ActivitiesLog\Repository\Value\InteractiveLoginData;

/**
 * Class ActivitiesLogInteractiveLoginService.
 */
class ActivitiesLogInteractiveLoginService
{
    /** @var \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService */
    private $activitiesLogRepositoryService;

    /** @var \Ibexa\Contracts\Core\Repository\UserService */
    private $userService;

    /**
     * ActivitiesLogInteractiveLoginService constructor.
     * @param \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService $activitiesLogRepositoryService
     * @param \Ibexa\Contracts\Core\Repository\UserService $userService
     */
    public function __construct(
        ActivitiesLogRepositoryService $activitiesLogRepositoryService,
        UserService $userService
    ) {
        $this->activitiesLogRepositoryService = $activitiesLogRepositoryService;
        $this->userService = $userService;
    }

    /**
     * @param $userId
     * @return \EzPlatform\ActivitiesLog\Repository\Value\InteractiveLoginData|null
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     */
    public function getInteractiveLoginData($userId): ?InteractiveLoginData
    {
        $userInteractiveLogin = $this->activitiesLogRepositoryService->getUserInteractiveLogin($userId);

        if (\count($userInteractiveLogin) === 0) {
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
