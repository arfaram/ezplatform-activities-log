<?php

namespace EzPlatform\ActivitiesLog\Repository\Pagination;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Trait ActivitiesLogPagination.
 */
trait ActivitiesLogPagination
{
    public function paginate($query, int $limit = 20, int $offset = 0): Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($query));
        $pager->setMaxPerPage((int) $limit);
        $pager->setCurrentPage(min(max($offset, 1), $pager->getNbPages()));

        return $pager;
    }
}
