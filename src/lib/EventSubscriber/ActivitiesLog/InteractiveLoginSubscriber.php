<?php

namespace EzPlatform\ActivitiesLog\EventSubscriber\ActivitiesLog;

use eZ\Publish\Core\MVC\Symfony\Security\UserInterface as eZUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent as BaseInteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Class InteractiveLoginSubscriber.
 */
final class InteractiveLoginSubscriber extends AbstractSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => [
                ['onInteractiveLogin', 100],
            ],
        ];
    }

    /**
     * @throws \Exception
     */
    public function onInteractiveLogin(BaseInteractiveLoginEvent $event): void
    {
        $token        = $event->getAuthenticationToken();
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
