<?php

namespace EzPlatform\ActivitiesLogBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use eZ\Publish\API\Repository\PermissionResolver;
use EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService;
use EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;

class ActivitiesLogController extends BaseController
{
    private const PAGINATION_PARAM_NAME = 'page';

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    /** @var \EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog */
    private $activitiesLog;

    /** @var \eZ\Publish\API\Repository\PermissionResolver */
    private $permissionResolver;

    /** @var \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService */
    private $activitiesLogRepositoryService;

    /** @var int */
    private $activitiesLogUiPanelPaginationLimit;

    /** @var \EzPlatform\ActivitiesLog\Repository\User */
    private $user;

    /**
     * ActivitiesLogController constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog $activitiesLog
     * @param \eZ\Publish\API\Repository\PermissionResolver $permissionResolver
     * @param \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService $activitiesLogRepositoryService
     * @param int $activitiesLogUiPanelPaginationLimit
     * @param $user
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ActivitiesLog $activitiesLog,
        PermissionResolver $permissionResolver,
        ActivitiesLogRepositoryService $activitiesLogRepositoryService,
        int $activitiesLogUiPanelPaginationLimit,
        $user
    ) {
        $this->entityManager = $entityManager;
        $this->activitiesLog = $activitiesLog;
        $this->permissionResolver = $permissionResolver;
        $this->activitiesLogRepositoryService = $activitiesLogRepositoryService;
        $this->activitiesLogUiPanelPaginationLimit = $activitiesLogUiPanelPaginationLimit;
        $this->user = $user;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function cockpitAllAction(Request $request)
    {
        if (!$this->permissionResolver->hasAccess('ezplatformactivitieslog', 'activitieslog_all')) {
            return $this->render(
                '@ezdesign/activities/cockpit.html.twig',
                [
                    'access_denied' => 'access_denied',
                ]
            );
        }
        $page = $request->query->get(self::PAGINATION_PARAM_NAME, 1);
        $pagerfanta = $this->activitiesLogRepositoryService->getPageResults($this->activitiesLogUiPanelPaginationLimit, $page);

        return $this->render(
            '@ezdesign/activities/cockpit.html.twig',
            [
                'pagination' => $pagerfanta,
            ]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function cockpitMyAction(Request $request)
    {
        if (!$this->permissionResolver->hasAccess('ezplatformactivitieslog', 'activitieslog_my')) {
            return $this->render(
                '@ezdesign/activities/cockpit.html.twig',
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
            '@ezdesign/activities/cockpit.html.twig',
            [
                'pagination' => $pagerfanta,
            ]
        );
    }
}
