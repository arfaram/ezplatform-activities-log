<?php

declare(strict_types=1);

namespace EzPlatform\ActivitiesLog\EventSubscriber\ActivitiesLog;

use Doctrine\ORM\EntityManagerInterface;
use Ibexa\Contracts\Core\Repository\PermissionResolver;
use EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AbstractSubscriber.
 */
abstract class AbstractSubscriber implements EventSubscriberInterface
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    protected $entityManager;

    /** @var \EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog */
    protected $activitiesLog;

    /** @var \Ibexa\Contracts\Core\Repository\PermissionResolver */
    protected $permissionResolver;

    /**
     * AbstractSubscriber constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog $activitiesLog
     * @param \Ibexa\Contracts\Core\Repository\PermissionResolver $permissionResolver
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ActivitiesLog $activitiesLog,
        PermissionResolver $permissionResolver
    ) {
        $this->entityManager = $entityManager;
        $this->activitiesLog = $activitiesLog;
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * @param $event
     * @return string
     */
    protected function getClassNameSpace($event): string
    {
        $getClassNameSpace = explode('\\', \get_class($event));

        return array_pop($getClassNameSpace);
    }

    /**
     * @param $event
     * @return $this
     * @throws \Exception
     */
    protected function setDefaultData($event)
    {
        $this->activitiesLog->setUserId($this->permissionResolver->getCurrentUserReference()->getUserId());
        $this->activitiesLog->setEventName($this->getClassNameSpace($event));
        $this->activitiesLog->setDate(new \DateTime());

        return $this;
    }

    /**
     * @return $this
     */
    protected function persistData()
    {
        $this->entityManager->persist($this->activitiesLog);
        $this->entityManager->flush();

        return $this;
    }
}
