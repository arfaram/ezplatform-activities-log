<?php

namespace EzPlatform\ActivitiesLog\Repository\Services;

use EzPlatform\ActivitiesLog\Repository\Pagination\ActivitiesLogPagination;
use EzPlatform\ActivitiesLog\Repository\Storage\Doctrine\ActivitiesLogRepository;
use Pagerfanta\Pagerfanta;

class ActivitiesLogRepositoryService
{
    use ActivitiesLogPagination;

    /** @var \EzPlatform\ActivitiesLog\Repository\Storage\Doctrine\ActivitiesLogRepository */
    private $activitiesLogRepository;

    public $query;

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
    public function getPageResults($limit = 20, $offset = 0): Pagerfanta
    {
        $this->query = $this->activitiesLogRepository->getQuery('queryBuilder');

        return $this->paginate($this->query, $limit, $offset);
    }

    /**
     * @param $userId
     * @param int $limit
     * @param int $offset
     * @return \Pagerfanta\Pagerfanta
     */
    public function getPageResultsPerUser($userId, $limit = 20, $offset = 0): Pagerfanta
    {
        $this->query = $this->activitiesLogRepository->getQuery('queryBuilderPerUserId', ['userId' => $userId]);

        return $this->paginate($this->query, $limit, $offset);
    }
}
