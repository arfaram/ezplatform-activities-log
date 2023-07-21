<?php

namespace EzPlatform\ActivitiesLog\Repository\Services;

use EzPlatform\ActivitiesLog\Repository\Pagination\ActivitiesLogPagination;
use EzPlatform\ActivitiesLog\Repository\Storage\Doctrine\ActivitiesLogRepository;
use Pagerfanta\Pagerfanta;

class ActivitiesLogRepositoryService
{
    use ActivitiesLogPagination;

    private ActivitiesLogRepository $activitiesLogRepository;

    public $queryBuilder;

    /**
     * ActivitiesLogRepositoryService constructor.
     */
    public function __construct(ActivitiesLogRepository $activitiesLogRepository)
    {
        $this->activitiesLogRepository = $activitiesLogRepository;
    }

    public function getPageResults(int $limit, int $offset): Pagerfanta
    {
        $this->queryBuilder = $this->activitiesLogRepository->getQueryBuilder('queryBuilder');

        return $this->paginate($this->queryBuilder, is_null($limit) ? 20 : $limit, is_null($offset) ? 0 : $offset);
    }

    public function getPageResultsPerUser($userId, int $limit, int $offset): Pagerfanta
    {
        $this->queryBuilder = $this->activitiesLogRepository->getQueryBuilder('queryBuilderPerUserId', ['userId' => $userId]);

        return $this->paginate($this->queryBuilder, is_null($limit) ? 20 : $limit, is_null($offset) ? 0 : $offset);
    }

    public function getUserInteractiveLogin($userId): mixed
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
