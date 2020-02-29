<?php

namespace EzPlatform\ActivitiesLog\Repository\Pagination;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

trait ActivitiesLogPagination
{
    /**
     * @param $query
     * @param int $limit
     * @param int $offset
     * @return \Pagerfanta\Pagerfanta
     */
    public function paginate($query, $limit = 20, $offset = 0): Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($query));
        $pager->setMaxPerPage((int) $limit);
        $pager->setCurrentPage(min(max($offset, 1), $pager->getNbPages()));

        return $pager;
    }
}
