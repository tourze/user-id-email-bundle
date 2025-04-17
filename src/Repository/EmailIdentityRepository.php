<?php

namespace Tourze\UserIDEmailBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;

/**
 * @method EmailIdentity|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailIdentity|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailIdentity[]    findAll()
 * @method EmailIdentity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailIdentityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailIdentity::class);
    }
}
