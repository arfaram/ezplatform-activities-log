<?php

namespace EzPlatform\ActivitiesLog\Tab\LocationView;

use Ibexa\Contracts\Core\Repository\PermissionResolver;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogInteractiveLoginService;
use EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService;
use Ibexa\AdminUi\Specification\ContentType\ContentTypeIsUser;
use Ibexa\Contracts\AdminUi\Tab\AbstractEventDispatchingTab;
use Ibexa\Contracts\AdminUi\Tab\ConditionalTabInterface;
use Ibexa\Contracts\AdminUi\Tab\OrderedTabInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class ActivitiesLogTab extends AbstractEventDispatchingTab implements OrderedTabInterface, ConditionalTabInterface
{
    /** @var string */
    public const URI_FRAGMENT = 'ibexa-tab-location-view-activitiesLog';

    /** @var \Ibexa\Contracts\Core\Repository\PermissionResolver */
    private $permissionResolver;

    /** @var \Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface */
    private $configResolver;

    /** @var \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService */
    private $activitiesLogRepositoryService;

    /** @var \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogInteractiveLoginService */
    private $activitiesLogInteractiveLogin;

    /**
     * ActivitiesLogTab constructor.
     * @param \Twig\Environment $twig
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Ibexa\Contracts\Core\Repository\PermissionResolver $permissionResolver
     * @param \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogRepositoryService $activitiesLogRepositoryService
     * @param \Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface $configResolver
     * @param \EzPlatform\ActivitiesLog\Repository\Services\ActivitiesLogInteractiveLoginService $activitiesLogInteractiveLogin
     */
    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        EventDispatcherInterface $eventDispatcher,
        PermissionResolver $permissionResolver,
        ActivitiesLogRepositoryService $activitiesLogRepositoryService,
        ConfigResolverInterface $configResolver,
        ActivitiesLogInteractiveLoginService $activitiesLogInteractiveLogin
    ) {
        parent::__construct($twig, $translator, $eventDispatcher);
        $this->permissionResolver = $permissionResolver;
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
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\BadStateException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
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
        return '@ibexadesign/content/tab/activitieslog_user_tab.html.twig';
    }

    /**
     * @param array $contextParameters
     * @return array
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     */
    public function getTemplateParameters(array $contextParameters = []): array
    {
        /** @var \Ibexa\Contracts\Core\Repository\Values\Content\Location $location */
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
