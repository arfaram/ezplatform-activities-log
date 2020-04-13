<?php

namespace EzPlatform\ActivitiesLog\EventSubscriber\ActivitiesLog;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent as BaseInteractiveLoginEvent;
use eZ\Publish\Core\MVC\Symfony\Security\UserInterface as eZUser;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class InteractiveLoginSubscriber.
 */
final class InteractiveLoginSubscriber extends AbstractSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => [
                ['onInteractiveLogin', 100],
            ],
        ];
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     * @throws \Exception
     */
    public function onInteractiveLogin(BaseInteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $originalUser = $token->getUser();

        if ($originalUser instanceof eZUser || !$originalUser instanceof UserInterface) {
            $this->activitiesLog
                ->setData(serialize(
                    [
                        'user' => $originalUser->getAPIUser()->getName(),
                    ]))
                ->setContentobjectId($originalUser->getAPIUser()->getUserId());

            $this->setDefaultData($event)->persistData();
        }
    }
}
