<?php

namespace EzPlatform\ActivitiesLogBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use eZ\Publish\API\Repository\PermissionResolver;
use EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogInteractiveLoginService;
use EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService;
use EzPlatform\ActivitiesLog\Repository\User;
use EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivitiesLogController extends BaseController
{
    private const PAGINATION_PARAM_NAME = 'page';

    private EntityManagerInterface $entityManager;
    private ActivitiesLog $activitiesLog;
    private PermissionResolver $permissionResolver;
    private ActivitiesLogRepositoryService $activitiesLogRepositoryService;
    private ActivitiesLogInteractiveLoginService $activitiesLogInteractiveLogin;
    private int $activitiesLogUiPanelPaginationLimit;
    private User $user;

    public function __construct(
        EntityManagerInterface $entityManager,
        ActivitiesLog $activitiesLog,
        PermissionResolver $permissionResolver,
        ActivitiesLogRepositoryService $activitiesLogRepositoryService,
        ActivitiesLogInteractiveLoginService $activitiesLogInteractiveLogin,
        int $activitiesLogUiPanelPaginationLimit,
        User $user
    ) {
        $this->entityManager                       = $entityManager;
        $this->activitiesLog                       = $activitiesLog;
        $this->permissionResolver                  = $permissionResolver;
        $this->activitiesLogRepositoryService      = $activitiesLogRepositoryService;
        $this->activitiesLogInteractiveLogin       = $activitiesLogInteractiveLogin;
        $this->activitiesLogUiPanelPaginationLimit = $activitiesLogUiPanelPaginationLimit;
        $this->user                                = $user;
    }

    /**
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function allActivitiesAction(Request $request): Response
    {
        if (!$this->permissionResolver->hasAccess('ezplatformactivitieslog', 'activitieslog_all')) {
            return $this->render(
                '@ezdesign/activities/activitieslog_view.html.twig',
                [
                    'access_denied' => 'access_denied',
                ]
            );
        }
        $page       = $request->query->get(self::PAGINATION_PARAM_NAME, 1);
        $pagerfanta = $this->activitiesLogRepositoryService->getPageResults($this->activitiesLogUiPanelPaginationLimit, $page);

        return $this->render(
            '@ezdesign/activities/activitieslog_view.html.twig',
            [
                'pagination' => $pagerfanta,
            ]
        );
    }

    /**
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function myActivitiesAction(Request $request): Response
    {
        if (!$this->permissionResolver->hasAccess('ezplatformactivitieslog', 'activitieslog_my')) {
            return $this->render(
                '@ezdesign/activities/activitieslog_view.html.twig',
                [
                    'access_denied' => 'access_denied',
                ]
            );
        }

        $userId = $this->user->getCurrentUserId();

        $page = $request->query->get(self::PAGINATION_PARAM_NAME, 1);

        $pagerfanta = $this->activitiesLogRepositoryService->getPageResultsPerUser(
            $userId,
            $this->activitiesLogUiPanelPaginationLimit,
            $page
        );

        return $this->render(
            '@ezdesign/activities/activitieslog_view.html.twig',
            [
                'pagination' => $pagerfanta,
                'userInteractiveLoginData' => $this->activitiesLogInteractiveLogin->getInteractiveLoginData($userId),
            ]
        );
    }
}
