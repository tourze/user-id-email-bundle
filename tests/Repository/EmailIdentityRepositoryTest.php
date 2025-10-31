<?php

namespace Tourze\UserIDEmailBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;
use Tourze\UserIDEmailBundle\Repository\EmailIdentityRepository;

/**
 * @internal
 */
#[CoversClass(EmailIdentityRepository::class)]
#[RunTestsInSeparateProcesses]
final class EmailIdentityRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void        // This method is required by AbstractIntegrationTestCase
    {
    }

    public function testGetClassName(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $this->assertEquals(EmailIdentity::class, $repository->getClassName());
    }

    public function testSave(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user = $this->createNormalUser('testuser');

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test@example.com');
        $entity->setUser($user);

        $repository->save($entity);

        $this->assertNotNull($entity->getId());
    }

    public function testRemove(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user = $this->createNormalUser('testuser');

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test@example.com');
        $entity->setUser($user);

        $repository->save($entity);
        $id = $entity->getId();

        $repository->remove($entity);

        $this->assertNull($repository->find($id));
    }

    public function testFindOneByWithOrderByClause(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user = $this->createNormalUser('testuser');

        $entity1 = new EmailIdentity();
        $entity1->setEmailAddress('b@example.com');
        $entity1->setUser($user);
        $repository->save($entity1);

        $entity2 = new EmailIdentity();
        $entity2->setEmailAddress('a@example.com');
        $entity2->setUser($user);
        $repository->save($entity2);

        $result = $repository->findOneBy([], ['emailAddress' => 'ASC']);
        $this->assertNotNull($result);
        $this->assertEquals('a@example.com', $result->getEmailAddress());
    }

    public function testFindByWithUserAssociation(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user = $this->createNormalUser('testuser');

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test@example.com');
        $entity->setUser($user);
        $repository->save($entity);

        $result = $repository->findBy(['user' => $user]);
        $this->assertNotEmpty($result);
        $this->assertEquals('test@example.com', $result[0]->getEmailAddress());
    }

    public function testCountWithUserAssociation(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user = $this->createNormalUser('testuser');

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test@example.com');
        $entity->setUser($user);
        $repository->save($entity);

        $count = $repository->count(['user' => $user]);
        $this->assertEquals(1, $count);
    }

    public function testFindOneByWithUserAssociation(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user = $this->createNormalUser('testuser');

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test@example.com');
        $entity->setUser($user);
        $repository->save($entity);

        $result = $repository->findOneBy(['user' => $user]);
        $this->assertInstanceOf(EmailIdentity::class, $result);
        $this->assertEquals('test@example.com', $result->getEmailAddress());
    }

    public function testFindByWithNullUser(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test-findby-null@example.com');
        $entity->setUser(null);
        $repository->save($entity);

        $result = $repository->findBy(['user' => null, 'emailAddress' => 'test-findby-null@example.com']);
        $this->assertNotEmpty($result);
        $this->assertEquals('test-findby-null@example.com', $result[0]->getEmailAddress());
    }

    public function testCountWithNullUser(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test-count-null@example.com');
        $entity->setUser(null);
        $repository->save($entity);

        $count = $repository->count(['user' => null, 'emailAddress' => 'test-count-null@example.com']);
        $this->assertEquals(1, $count);
    }

    public function testFindOneByWithNullUser(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test-findoneby-null@example.com');
        $entity->setUser(null);
        $repository->save($entity);

        $result = $repository->findOneBy(['user' => null, 'emailAddress' => 'test-findoneby-null@example.com']);
        $this->assertInstanceOf(EmailIdentity::class, $result);
        $this->assertEquals('test-findoneby-null@example.com', $result->getEmailAddress());
    }

    public function testFindOneByWithOrderByLogic(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user = $this->createNormalUser('testuser');

        $entity1 = new EmailIdentity();
        $entity1->setEmailAddress('z@example.com');
        $entity1->setUser($user);
        $repository->save($entity1);

        $entity2 = new EmailIdentity();
        $entity2->setEmailAddress('a@example.com');
        $entity2->setUser($user);
        $repository->save($entity2);

        $result = $repository->findOneBy(['user' => $user], ['emailAddress' => 'ASC']);
        $this->assertNotNull($result);
        $this->assertEquals('a@example.com', $result->getEmailAddress());

        $result = $repository->findOneBy(['user' => $user], ['emailAddress' => 'DESC']);
        $this->assertNotNull($result);
        $this->assertEquals('z@example.com', $result->getEmailAddress());
    }

    public function testCountWithAssociationQuery(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user1 = $this->createNormalUser('user1');
        $user2 = $this->createNormalUser('user2');

        $entity1 = new EmailIdentity();
        $entity1->setEmailAddress('test1@example.com');
        $entity1->setUser($user1);
        $repository->save($entity1);

        $entity2 = new EmailIdentity();
        $entity2->setEmailAddress('test2@example.com');
        $entity2->setUser($user2);
        $repository->save($entity2);

        $count1 = $repository->count(['user' => $user1]);
        $this->assertEquals(1, $count1);

        $count2 = $repository->count(['user' => $user2]);
        $this->assertEquals(1, $count2);
    }

    public function testFindByWithAssociationQuery(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user1 = $this->createNormalUser('user1');
        $user2 = $this->createNormalUser('user2');

        $entity1 = new EmailIdentity();
        $entity1->setEmailAddress('test1@example.com');
        $entity1->setUser($user1);
        $repository->save($entity1);

        $entity2 = new EmailIdentity();
        $entity2->setEmailAddress('test2@example.com');
        $entity2->setUser($user2);
        $repository->save($entity2);

        $result1 = $repository->findBy(['user' => $user1]);
        $this->assertCount(1, $result1);
        $this->assertEquals('test1@example.com', $result1[0]->getEmailAddress());

        $result2 = $repository->findBy(['user' => $user2]);
        $this->assertCount(1, $result2);
        $this->assertEquals('test2@example.com', $result2[0]->getEmailAddress());
    }

    public function testFindOneByWithAssociationQuery(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user1 = $this->createNormalUser('user1');
        $user2 = $this->createNormalUser('user2');

        $entity1 = new EmailIdentity();
        $entity1->setEmailAddress('test1@example.com');
        $entity1->setUser($user1);
        $repository->save($entity1);

        $entity2 = new EmailIdentity();
        $entity2->setEmailAddress('test2@example.com');
        $entity2->setUser($user2);
        $repository->save($entity2);

        $result1 = $repository->findOneBy(['user' => $user1]);
        $this->assertInstanceOf(EmailIdentity::class, $result1);
        $this->assertEquals('test1@example.com', $result1->getEmailAddress());

        $result2 = $repository->findOneBy(['user' => $user2]);
        $this->assertInstanceOf(EmailIdentity::class, $result2);
        $this->assertEquals('test2@example.com', $result2->getEmailAddress());
    }

    public function testFindByWithNullFieldQuery(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test-findby-null-field@example.com');
        $entity->setUser(null);
        $repository->save($entity);

        $result = $repository->findBy(['user' => null, 'emailAddress' => 'test-findby-null-field@example.com']);
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals('test-findby-null-field@example.com', $result[0]->getEmailAddress());
    }

    public function testCountWithNullFieldQuery(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test-count-null@example.com');
        $entity->setUser(null);
        $repository->save($entity);

        $count = $repository->count(['user' => null]);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testFindOneByAssociationUserShouldReturnMatchingEntity(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user = $this->createNormalUser('testuser');

        $entity = new EmailIdentity();
        $entity->setEmailAddress('test@example.com');
        $entity->setUser($user);
        $repository->save($entity);

        $result = $repository->findOneBy(['user' => $user]);
        $this->assertInstanceOf(EmailIdentity::class, $result);
        $this->assertEquals('test@example.com', $result->getEmailAddress());
    }

    public function testCountByAssociationUserShouldReturnCorrectNumber(): void
    {
        $repository = self::getService(EmailIdentityRepository::class);
        $user = $this->createNormalUser('testuser');

        $entity1 = new EmailIdentity();
        $entity1->setEmailAddress('test1@example.com');
        $entity1->setUser($user);
        $repository->save($entity1);

        $entity2 = new EmailIdentity();
        $entity2->setEmailAddress('test2@example.com');
        $entity2->setUser($user);
        $repository->save($entity2);

        $count = $repository->count(['user' => $user]);
        $this->assertEquals(2, $count);
    }

    protected function createNewEntity(): object
    {
        $entity = new EmailIdentity();

        // 设置基本字段
        $entity->setEmailAddress('test-' . uniqid() . '@example.com');

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<EmailIdentity>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(EmailIdentityRepository::class);
    }
}
