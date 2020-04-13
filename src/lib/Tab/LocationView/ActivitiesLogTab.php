<?php

namespace EzPlatform\ActivitiesLog\Tab\LocationView;

use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogInteractiveLoginService;
use EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService;
use EzSystems\EzPlatformAdminUi\Specification\ContentType\ContentTypeIsUser;
use EzSystems\EzPlatformAdminUi\Tab\AbstractEventDispatchingTab;
use EzSystems\EzPlatformAdminUi\Tab\ConditionalTabInterface;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class ActivitiesLogTab extends AbstractEventDispatchingTab implements OrderedTabInterface, ConditionalTabInterface
{
    /** @var string */
    public const URI_FRAGMENT = 'ez-tab-location-view-activitiesLog';

    /** @var \eZ\Publish\API\Repository\PermissionResolver */
    private $permissionResolver;

    /** @var \eZ\Publish\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    /** @var \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService */
    private $activitiesLogRepositoryService;

    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    /** @var \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogInteractiveLoginService */
    private $activitiesLogInteractiveLogin;

    /**
     * ActivitiesLogTab constructor.
     * @param \Twig\Environment $twig
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \eZ\Publish\API\Repository\PermissionResolver $permissionResolver
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService $activitiesLogRepositoryService
     * @param \eZ\Publish\Core\MVC\ConfigResolverInterface $configResolver
     * @param \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogInteractiveLoginService $activitiesLogInteractiveLogin
     */
    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        EventDispatcherInterface $eventDispatcher,
        PermissionResolver $permissionResolver,
        RequestStack $requestStack,
        ActivitiesLogRepositoryService $activitiesLogRepositoryService,
        ConfigResolverInterface $configResolver,
        ActivitiesLogInteractiveLoginService $activitiesLogInteractiveLogin
    ) {
        parent::__construct($twig, $translator, $eventDispatcher);
        $this->permissionResolver = $permissionResolver;
        $this->requestStack = $requestStack;
        $this->activitiesLogRepositoryService = $activitiesLogRepositoryService;
        $this->configResolver = $configResolver;
        $this->activitiesLogInteractiveLogin = $activitiesLogInteractiveLogin;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return 'activitiesLog';
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1000;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        /** @Desc("Activities") */
        return $this->translator->trans('tab.name.activitieslog', [], 'activities');
    }

    /**
     * @param array $parameters
     * @return bool
     * @throws \eZ\Publish\API\Repository\Exceptions\BadStateException
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function evaluate(array $parameters): bool
    {
        if (false === $this->permissionResolver->canUser('content', 'activitieslog', $parameters['content'])) {
            return false;
        }
        $contentType = $parameters['contentType'];

        //Note: this menu item will be ONLY displayed on user content object level
        $isUser = new ContentTypeIsUser($this->configResolver->getParameter('user_content_type_identifier'));

        return $isUser->isSatisfiedBy($contentType);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@ezdesign/content/tab/activitieslog_user_tab.html.twig';
    }

    /**
     * @param array $contextParameters
     * @return array
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function getTemplateParameters(array $contextParameters = []): array
    {
        /** @var \eZ\Publish\API\Repository\Values\Content\Location $location */
        $location = $contextParameters['location'];
        $userId = $location->getContentInfo()->id;

        $activitiesLogPaginationParams = $contextParameters['activities_pagination_params'];

        $pagerfanta = $this->activitiesLogRepositoryService->getPageResultsPerUser(
            $userId,
            $activitiesLogPaginationParams['limit'],
            $activitiesLogPaginationParams['page']
        );

        $viewParameters = [
            'pagination' => $pagerfanta,
            'activities_pagination_params' => $activitiesLogPaginationParams,
            'userInteractiveLoginData' => $this->activitiesLogInteractiveLogin->getInteractiveLoginData($userId),
        ];

        return $viewParameters;
    }
}
