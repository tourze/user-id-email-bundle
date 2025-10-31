<?php

namespace Tourze\UserIDEmailBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;

/**
 * @extends ServiceEntityRepository<EmailIdentity>
 */
#[Autoconfigure(public: true)]
#[AsRepository(entityClass: EmailIdentity::class)]
class EmailIdentityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailIdentity::class);
    }

    public function save(EmailIdentity $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EmailIdentity $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
