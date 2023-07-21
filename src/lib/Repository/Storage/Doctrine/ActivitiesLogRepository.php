<?php

namespace EzPlatform\ActivitiesLog\Repository\Storage\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog;
use EzPlatform\ActivitiesLogBundle\Helper\DoctrineHelper;

/**
 * Class ActivitiesLogRepository.
 */
class ActivitiesLogRepository extends ServiceEntityRepository
{
    public QueryBuilder $queryBuilder;
    public DoctrineHelper $doctrineHelper;

    /**
     * ActivitiesLogRepository constructor.
     */
    public function __construct(ManagerRegistry $registry, DoctrineHelper $doctrineHelper)
    {
        parent::__construct($registry, ActivitiesLog::class);
        $this->doctrineHelper = $doctrineHelper;
    }

    public function queryBuilder(): QueryBuilder
    {
        $this->queryBuilder = $this->doctrineHelper->getCurrentEntityManager()->createQueryBuilder('a')
            ->select('a')
            ->from('EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog', 'a')
            ->orderBy('a.date', 'DESC');

        return $this->queryBuilder;
    }

    public function queryBuilderPerUserId(array $params): QueryBuilder
    {
        $this->queryBuilder = $this->doctrineHelper->getCurrentEntityManager()->createQueryBuilder('a')
            ->select('a')
            ->from('EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog', 'a')
            ->where('a.userId = ?1')
            ->setParameter(1, $params['userId'])
            ->orderBy('a.date', 'DESC');

        return $this->queryBuilder;
    }

    public function queryUserInteractiveLogin(array $params): QueryBuilder
    {
        $this->queryBuilder = $this->doctrineHelper->getCurrentEntityManager()->createQueryBuilder('a')
            ->select('a')
            ->from('EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog', 'a')
            ->andWhere('a.userId = ?1')
            ->andWhere('a.eventName = ?2')
            ->setParameter(1, $params['userId'])
            ->setParameter(2, $params['eventName'])
            ->orderBy('a.date', 'DESC');

        return $this->queryBuilder;
    }

    public function getQueryBuilder($method, array $params = null)
    {
        return $this->{$method}($params);
    }
}
