<?php

declare(strict_types=1);

namespace EzPlatform\ActivitiesLog\EventSubscriber\ActivitiesLog;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use eZ\Publish\API\Repository\PermissionResolver;
use EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog;
use EzPlatform\ActivitiesLogBundle\Helper\DoctrineHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AbstractSubscriber.
 */
abstract class AbstractSubscriber implements EventSubscriberInterface
{
    protected EntityManagerInterface $entityManager;
    protected ActivitiesLog $activitiesLog;

    protected PermissionResolver $permissionResolver;
    protected DoctrineHelper $doctrineHelper;

    public function __construct(
        EntityManagerInterface $entityManager,
        ActivitiesLog $activitiesLog,
        PermissionResolver $permissionResolver,
        DoctrineHelper $doctrineHelper
    ) {
        $this->entityManager      = $entityManager;
        $this->activitiesLog      = $activitiesLog;
        $this->permissionResolver = $permissionResolver;
        $this->doctrineHelper     = $doctrineHelper;
    }

    protected function getClassNameSpace($event): string
    {
        $getClassNameSpace = explode('\\', \get_class($event));

        return array_pop($getClassNameSpace);
    }

    /**
     * @throws \Exception
     *
     * @return $this
     */
    protected function setDefaultData($event)
    {
        $this->activitiesLog->setUserId($this->permissionResolver->getCurrentUserReference()->getUserId());
        $this->activitiesLog->setEventName($this->getClassNameSpace($event));
        $this->activitiesLog->setDate(new \DateTime());

        return $this;
    }

    /**
     * @throws ORMException
     *
     * @return $this
     */
    protected function persistData()
    {
        $this->doctrineHelper->getCurrentEntityManager()->persist($this->activitiesLog);
        $this->doctrineHelper->getCurrentEntityManager()->flush();

        return $this;
    }
}
