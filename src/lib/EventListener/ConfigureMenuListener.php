<?php

namespace EzPlatform\ActivitiesLog\EventListener;

use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

class ConfigureMenuListener implements TranslationContainerInterface
{
    /** @var string */
    private const ACTIVITIES_LOG_MENU_ITEM = 'activities__log__menu__item';

    /** @var string */
    private const ACTIVITIES_LOG_MENU_ALL = 'activities__log__menu__all';

    /** @var string */
    private const ACTIVITIES_LOG_MENU_MY = 'activities__log__menu__my';

    /** @var \eZ\Publish\API\Repository\PermissionResolver */
    private $permissionResolver;

    /**
     * ConfigureMenuListener constructor.
     * @param \eZ\Publish\API\Repository\PermissionResolver $permissionResolver
     */
    public function __construct(
        PermissionResolver $permissionResolver
    ) {
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * @param \EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent $event
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $root = $event->getMenu();
        if ($this->permissionResolver->hasAccess('ezplatformactivitieslog', 'activitieslog_manage')) {
            $root->addChild(
                self::ACTIVITIES_LOG_MENU_ITEM,
                [
                    'extras' => ['translation_domain' => 'menu'],
                ]
            );
        }
        if ($this->permissionResolver->hasAccess('ezplatformactivitieslog', 'activitieslog_all')) {
            $root[self::ACTIVITIES_LOG_MENU_ITEM]->addChild(
                self::ACTIVITIES_LOG_MENU_ALL,
                [
                    'route' => 'admin_log_activities_all',
                    'extras' => ['translation_domain' => 'menu'],
                ]
            );
        }

        if ($this->permissionResolver->hasAccess('ezplatformactivitieslog', 'activitieslog_my')) {
            $root[self::ACTIVITIES_LOG_MENU_ITEM]->addChild(
                self::ACTIVITIES_LOG_MENU_MY,
                [
                    'route' => 'admin_log_activities_my',
                    'extras' => ['translation_domain' => 'menu'],
                ]
            );
        }
    }

    /** @return array  */
    public static function getTranslationMessages()
    {
        return [
            (new Message(self::ACTIVITIES_LOG_MENU_ITEM, 'menu'))->setDesc('Activities Log'),
            (new Message(self::ACTIVITIES_LOG_MENU_ALL, 'menu'))->setDesc('All Logs'),
            (new Message(self::ACTIVITIES_LOG_MENU_MY, 'menu'))->setDesc('My Logs'),
        ];
    }
}
