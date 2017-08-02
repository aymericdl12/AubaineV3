<?php

namespace Aubaine\PlatformBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * AubaineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AubaineRepository extends DocumentRepository
{
	public function findAllOrderedByName()
    {
        return $this->createQueryBuilder()
            ->sort('dateCreated', 'DESC')
            ->getQuery()
            ->execute()
        ;
    }
    public function getAubaines($current_day_datetime, $page, $nbPerPage)
	{
		return $this->createQueryBuilder()
		  ->sort('date', 'DESC')
		  // ->field('end')->lte($current_day_datetime)
		  ->limit($nbPerPage)
		  ->skip(($page-1) * $nbPerPage)
		  ->getQuery()
          ->execute()
		;
	}
    public function getAubainesLive($current_day_datetime, $categories,$city)
	{
		return $this->createQueryBuilder()
		  ->sort('end', 'ASC')
		  ->field('end')->gte($current_day_datetime)
		  ->field('category')->in($categories)
		  ->field('city')->equals($city)
		  ->getQuery()
          ->execute()
		;
	}
    public function getLastAubaines($types, $max)
	{
		return $this->createQueryBuilder()
		  ->field('type')->in($types)
		  ->sort('date', 'DESC')
		  ->limit($max)
		  ->getQuery()
          ->execute()
		;
	}
    public function getCurrentAubaines($placeId,$max)
	{
		return $this->createQueryBuilder()
			->field('end')->gte($max)
			->field('placeId')->equals($placeId)
			->field('type')->in(array(2,3))
			->getQuery()
			->execute()
		;
	}
    public function getOldAubaines($placeId,$max)
	{
		return $this->createQueryBuilder()
			->field('end')->lte($max)
			->field('placeId')->equals($placeId)
			->field('type')->in(array(2,3))
			->getQuery()
			->execute()
		;
	}
    public function getPermanentAubaines($placeId)
	{
		return $this->createQueryBuilder()
			->field('type')->equals(1)
			->field('placeId')->equals($placeId)
			->getQuery()
			->execute()
		;
	}
}
