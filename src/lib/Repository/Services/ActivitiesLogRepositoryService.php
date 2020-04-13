<?php

namespace EzPlatform\ActivitiesLog\Repository\Services;

use EzPlatform\ActivitiesLog\Repository\Pagination\ActivitiesLogPagination;
use EzPlatform\ActivitiesLog\Repository\Storage\Doctrine\ActivitiesLogRepository;
use Pagerfanta\Pagerfanta;

class ActivitiesLogRepositoryService
{
    use ActivitiesLogPagination;

    /** @var \EzPlatform\ActivitiesLog\Repository\Storage\Doctrine\ActivitiesLogRepository $activitiesLogRepository */
    private $activitiesLogRepository;

    /** @var \Doctrine\ORM\QueryBuilder\QueryBuilder $queryBuilder */
    public $queryBuilder;

    /**
     * ActivitiesLogRepositoryService constructor.
     * @param \EzPlatform\ActivitiesLog\Repository\Storage\Doctrine\ActivitiesLogRepository $activitiesLogRepository
     */
    public function __construct(ActivitiesLogRepository $activitiesLogRepository)
    {
        $this->activitiesLogRepository = $activitiesLogRepository;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return \Pagerfanta\Pagerfanta
     */
    public function getPageResults( $limit , $offset): Pagerfanta
    {
        $this->queryBuilder = $this->activitiesLogRepository->getQueryBuilder('queryBuilder');

        return $this->paginate($this->queryBuilder, is_null($limit) ? 20: $limit, is_null($offset) ? 0: $offset);
    }

    /**
     * @param $userId
     * @param int $limit
     * @param int $offset
     * @return \Pagerfanta\Pagerfanta
     */
    public function getPageResultsPerUser($userId, $limit , $offset ): Pagerfanta
    {
        $this->queryBuilder = $this->activitiesLogRepository->getQueryBuilder('queryBuilderPerUserId', ['userId' => $userId]);

        return $this->paginate($this->queryBuilder, is_null($limit) ? 20: $limit, is_null($offset) ? 0: $offset);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserInteractiveLogin($userId)
    {
        $this->queryBuilder = $this->activitiesLogRepository->getQueryBuilder(
            'queryUserInteractiveLogin',
                [
                    'userId' => $userId,
                    'eventName' => 'InteractiveLoginEvent',
                ]);

        return $this->queryBuilder->getQuery()->execute();
    }
}
