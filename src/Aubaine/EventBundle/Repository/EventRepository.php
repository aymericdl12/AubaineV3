<?php

namespace Aubaine\EventBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * AubaineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends DocumentRepository
{
    public function getEvents($current_day_datetime, $page, $nbPerPage)
	{
		return $this->createQueryBuilder()
		  ->sort('date', 'ASC')
		  ->limit($nbPerPage)
		  ->skip(($page-1) * $nbPerPage)
		  ->getQuery()
          ->execute()
		;
	}
}
