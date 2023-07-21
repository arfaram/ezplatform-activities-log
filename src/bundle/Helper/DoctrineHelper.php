<?php

namespace EzPlatform\ActivitiesLogBundle\Helper;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use eZ\Publish\Core\MVC\Symfony\SiteAccess\SiteAccessServiceInterface;

class DoctrineHelper
{
    private ManagerRegistry $managerRegistry;
    private SiteAccessServiceInterface $siteAccessService;

    public function __construct(ManagerRegistry $managerRegistry, SiteAccessServiceInterface $siteAccessService)
    {
        $this->managerRegistry   = $managerRegistry;
        $this->siteAccessService = $siteAccessService;
    }

    public function getCurrentEntityManager(): EntityManager
    {
        return $this->managerRegistry->getManager($this->getCurrentEntityManagerName());
    }

    public function getRepository(string $entityName): ObjectRepository
    {
        return $this->getCurrentEntityManager()->getRepository($entityName);
    }

    public function getCurrentEntityManagerName(): string
    {
        $siteAccessGroupsArray = [];
        $entityManagerName     = 'external';

        foreach ($this->siteAccessService->getCurrent()->groups as $siteAccessGroup) {
            $siteAccessGroupsArray[] = explode('_', $siteAccessGroup->getName())[0];
        }

        $arrayIntersectSiteAccesses = array_values(array_intersect(array_unique($siteAccessGroupsArray), array_keys($this->managerRegistry->getManagerNames())));
        if ($arrayIntersectSiteAccesses) {
            $entityManagerName = $arrayIntersectSiteAccesses[0];
        }

        return $entityManagerName;
    }
}
