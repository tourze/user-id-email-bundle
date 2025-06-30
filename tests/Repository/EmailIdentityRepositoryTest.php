<?php

namespace Tourze\UserIDEmailBundle\Tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;
use Tourze\UserIDEmailBundle\Repository\EmailIdentityRepository;

class EmailIdentityRepositoryTest extends TestCase
{
    private ManagerRegistry $registry;
    private EntityManagerInterface $entityManager;
    private EmailIdentityRepository $repository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->registry = $this->createMock(ManagerRegistry::class);

        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->name = EmailIdentity::class;

        $this->entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with(EmailIdentity::class)
            ->willReturn($classMetadata);

        $this->registry->expects($this->any())
            ->method('getManagerForClass')
            ->with(EmailIdentity::class)
            ->willReturn($this->entityManager);

        $this->repository = new EmailIdentityRepository($this->registry);
    }

    public function testGetClassName(): void
    {
        $this->assertEquals(EmailIdentity::class, $this->repository->getClassName());
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(EmailIdentityRepository::class, $this->repository);
    }
}