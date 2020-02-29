<?php

namespace EzPlatform\ActivitiesLog\Repository\Storage\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use EzPlatform\ActivitiesLogBundle\Entity\ActivitiesLog;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ActivitiesLogRepository extends ServiceEntityRepository
{
    /** @var QueryBuilder $queryBuilder */
    public $queryBuilder;

    /**
     * ActivitiesLogRepository constructor.
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActivitiesLog::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function queryBuilder(): QueryBuilder
    {
        $this->queryBuilder = $this->createQueryBuilder('a')
            ->select('a')
            ->orderBy('a.date', 'DESC');

        return $this->queryBuilder;
    }

    /**
     * @param array $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function queryBuilderPerUserId(array $params): QueryBuilder
    {
        $this->queryBuilder = $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.userId = ?1')
            ->setParameter(1, $params['userId'])
            ->orderBy('a.date', 'DESC');

        return $this->queryBuilder;
    }

    /**
     * @param $method
     * @param array|null $params
     * @return mixed
     */
    public function getQuery($method, array $params = null)
    {
        return $this->{$method}($params);
    }
}
