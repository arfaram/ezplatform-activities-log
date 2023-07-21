<?php

namespace EzPlatform\ActivitiesLog\EventSubscriber;

use EzSystems\EzPlatformAdminUi\Tab\Event\TabEvent;
use EzSystems\EzPlatformAdminUi\Tab\Event\TabEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ActivitiesLogTabSubscriber implements EventSubscriberInterface
{
    private const PAGINATION_PARAM_NAME = 'page';

    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    /** @var int */
    private $activitiesLogUserTabPaginationLimit;

    /**
     * ActivitiesTabSubscriber constructor.
     */
    public function __construct(
        RequestStack $requestStack,
        $activitiesLogUserTabPaginationLimit
    ) {
        $this->requestStack                        = $requestStack;
        $this->activitiesLogUserTabPaginationLimit = $activitiesLogUserTabPaginationLimit;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [TabEvents::TAB_PRE_RENDER => 'onTabPreRender'];
    }

    public function onTabPreRender(TabEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        $page = $request->query->get(self::PAGINATION_PARAM_NAME); // array: page['activities']

        $event->setParameters(
            [
                'activities_pagination_params' => [
                    'route_name' => $request->attributes->get('_route'),
                    'route_params' => $request->attributes->get('_route_params'),
                    'page' => $page['activities'] ?? 1,
                    'limit' => $this->activitiesLogUserTabPaginationLimit,
                ],
            ]
        );
    }
}
