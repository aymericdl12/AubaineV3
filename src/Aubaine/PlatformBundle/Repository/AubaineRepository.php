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
		  ->sort('date', 'ASC')
		  // ->field('date')->gte($current_day_datetime)
		  ->limit($nbPerPage)
		  ->skip(($page-1) * $nbPerPage)
		  ->getQuery()
          ->execute()
		;
	}
    public function getAubainesByAuthor($userId, $month_first_day, $month_last_day)
	{
		return $this->createQueryBuilder()
			->field('date')->lte($month_last_day)
			->field('date')->gte($month_first_day)
			->field('idAuthor')->equals($userId)
			->getQuery()
			->execute()
		;
	}
    public function getCurrentAubaineByAuthor($userId, $date)
	{
		return $this->createQueryBuilder()
			->field('date')->equals($date)
			->field('idAuthor')->equals($userId)
			->getQuery()
			->getSingleResult()
		;
	}
    public function getAubaineByDateAndCity($date, $city)
	{
		return $this->createQueryBuilder()
			->field('date')->equals($date)
			->field('city')->equals($city)
			->getQuery()
			->execute()
		;
	}
    public function getLastAubaines($type, $max)
	{
		return $this->createQueryBuilder()
		  ->field('type')->equals($type)
		  ->sort('date', 'ASC')
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
			->field('permanent')->equals(False)
			->getQuery()
			->execute()
		;
	}
    public function getOldAubaines($placeId,$max)
	{
		return $this->createQueryBuilder()
			->field('end')->lte($max)
			->field('placeId')->equals($placeId)
			->field('permanent')->equals(False)
			->getQuery()
			->execute()
		;
	}
    public function getPermanentAubaines($placeId)
	{
		return $this->createQueryBuilder()
			->field('permanent')->equals(True)
			->field('placeId')->equals($placeId)
			->getQuery()
			->execute()
		;
	}
}
