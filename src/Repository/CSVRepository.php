<?php

namespace App\Repository;

use App\Entity\CSV;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CSV|null find($id, $lockMode = null, $lockVersion = null)
 * @method CSV|null findOneBy(array $criteria, array $orderBy = null)
 * @method CSV[]    findAll()
 * @method CSV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CSVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CSV::class);
    }

    /**
    * @return CSV[] Returns an array of CSV objects
    */
    public function getData()
    {
        return $this->createQueryBuilder('c')
            // ->andWhere('c.exampleField = :val')
            // ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    
    public function getTotalCount()
    {
        $qb=$this->createQueryBuilder('c');
        $qb->select('count(1) as count');
        return $qb->getQuery()->getResult();
    }

    public function findOneBySomeField($searchValue,$columnName,$columnSortOrder,$row,$rowperpage)
    {
        $qb=$this->createQueryBuilder('c')
            ->orderBy('c.'.$columnName,$columnSortOrder)
            ->setFirstResult($row)
            ->setMaxResults($rowperpage);
        if(!empty($searchValue))
        {
            $qb->orWhere('c.Name = :val')
            ->orWhere('c.age = :val')
            ->orWhere('c.ReportingManager = :val')
            ->orWhere('c.Salary = :val')
            ->orWhere('c.Department = :val')
            ->setParameter('val',$searchValue);
        }

        return  $qb->getQuery()
            // ->getSql();
            ->getResult();
    }
    public function findOneBySomeFieldCount($searchValue,$columnName,$columnSortOrder,$row,$rowperpage)
    {
        $qb=$this->createQueryBuilder('c');
        $qb->select('count(1) as count')
            ->orderBy('c.'.$columnName,$columnSortOrder);

        if(!empty($searchValue))
        {
            $qb->orWhere('c.Name = :val')
            ->orWhere('c.age = :val')
            ->orWhere('c.ReportingManager = :val')
            ->orWhere('c.Salary = :val')
            ->orWhere('c.Department = :val')
            ->setParameter('val',$searchValue);
        }

        return  $qb->getQuery()
            // ->getSql();
            ->getResult();

    }
    public function addInCSV($data)
    {
        $entityManager = $this->getEntityManager();
        $insert = new CSV();
        $insert->setName($data['name']);
        $insert->setAge($data['age']);
        $insert->setDob($data['dob']);
        $insert->setReportingManager($data['ReportingManager']);
        $insert->setSalary($data['Salary']);
        $insert->setDepartment($data['Department']);
        $entityManager->persist($insert);
        $entityManager->flush();
        return $insert->getId();
    }
    public function deleteAllData()
    {
        $entityManager = $this->getEntityManager();
        $csvdata = $entityManager->getRepository(CSV::class)->findAll();
        foreach ($csvdata as $entity) {
            $entityManager->remove($entity);
        }
        $entityManager->flush();
    }
}
