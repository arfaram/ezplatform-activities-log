<?php

namespace EzPlatform\ActivitiesLog\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\Core\MVC\Symfony\Event\SignalEvent;
use eZ\Publish\Core\MVC\Symfony\MVCEvents;
use eZ\Publish\Core\SignalSlot\Signal\ContentService\UpdateContentSignal;
use EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ActivitiesLogSignalListener implements EventSubscriberInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var \EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog
     */
    private $activitiesLog;
    /**
     * @var \eZ\Publish\API\Repository\PermissionResolver
     */
    private $permissionResolver;

    public function __construct(
        EntityManagerInterface $entityManager,
        ActivitiesLog $activitiesLog,
        PermissionResolver $permissionResolver
    ) {
        $this->entityManager = $entityManager;
        $this->activitiesLog = $activitiesLog;
        $this->permissionResolver = $permissionResolver;
    }

    public function onAPISignal(SignalEvent $event)
    {
        $signal = $event->getSignal();

        if ($signal instanceof  UpdateContentSignal) {
            if ($signal->contentId) {
                $this->activitiesLog->setContentobjectId($signal->contentId);
            }
        }
        $this->activitiesLog->setUserId($this->permissionResolver->getCurrentUserReference()->getUserId());

        $getClassNameSpace = explode('\\', \get_class($signal));
        $getSignalClass = array_pop($getClassNameSpace);
        $this->activitiesLog->setEventName($getSignalClass);
        $this->activitiesLog->setData(serialize($signal));
        $this->activitiesLog->setDate(new \DateTime());

        $this->entityManager->persist($this->activitiesLog);

        $this->entityManager->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            MVCEvents::API_SIGNAL => 'onAPISignal',
        ];
    }
}
