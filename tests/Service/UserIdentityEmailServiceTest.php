<?php

namespace Tourze\UserIDEmailBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;
use Tourze\UserIDEmailBundle\Repository\EmailIdentityRepository;
use Tourze\UserIDEmailBundle\Service\UserIdentityEmailService;

/**
 * @internal
 */
#[CoversClass(UserIdentityEmailService::class)]
#[RunTestsInSeparateProcesses]
final class UserIdentityEmailServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
    }

    private function getUserIdentityEmailService(): UserIdentityEmailService
    {
        return self::getService(UserIdentityEmailService::class);
    }

    private function getEmailIdentityRepository(): EmailIdentityRepository
    {
        return self::getService(EmailIdentityRepository::class);
    }

    public function testFindByTypeWithEmailTypeAndExistingValue(): void
    {
        $service = $this->getUserIdentityEmailService();
        $repository = $this->getEmailIdentityRepository();

        $user = $this->createNormalUser('test@example.com', 'password');
        $emailAddress = 'test@example.com';

        $emailIdentity = new EmailIdentity();
        $emailIdentity->setEmailAddress($emailAddress);
        $emailIdentity->setUser($user);
        $repository->save($emailIdentity);

        $result = $service->findByType(EmailIdentity::IDENTITY_TYPE, $emailAddress);

        $this->assertInstanceOf(EmailIdentity::class, $result);
        $this->assertEquals($emailAddress, $result->getEmailAddress());
    }

    public function testFindByTypeWithEmailTypeAndNonExistingValue(): void
    {
        $service = $this->getUserIdentityEmailService();

        $emailAddress = 'nonexistent@example.com';

        $result = $service->findByType(EmailIdentity::IDENTITY_TYPE, $emailAddress);

        $this->assertNull($result);
    }

    public function testFindByTypeWithNonEmailType(): void
    {
        $service = $this->getUserIdentityEmailService();

        $type = 'phone';
        $value = '1234567890';

        $result = $service->findByType($type, $value);

        $this->assertNull($result);
    }

    public function testFindByUserWithUserHavingEmailIdentities(): void
    {
        $service = $this->getUserIdentityEmailService();
        $repository = $this->getEmailIdentityRepository();

        $user = $this->createNormalUser('user@example.com', 'password');

        $emailIdentity1 = new EmailIdentity();
        $emailIdentity1->setEmailAddress('test1@example.com');
        $emailIdentity1->setUser($user);
        $repository->save($emailIdentity1);

        $emailIdentity2 = new EmailIdentity();
        $emailIdentity2->setEmailAddress('test2@example.com');
        $emailIdentity2->setUser($user);
        $repository->save($emailIdentity2);

        $result = iterator_to_array($service->findByUser($user));

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(EmailIdentity::class, $result);
    }

    public function testFindByUserWithUserHavingNoEmailIdentities(): void
    {
        $service = $this->getUserIdentityEmailService();

        $user = $this->createNormalUser('user@example.com', 'password');

        $result = iterator_to_array($service->findByUser($user));

        $this->assertEmpty($result);
    }

    public function testFindByUserWithUserHavingNoIdentities(): void
    {
        $service = $this->getUserIdentityEmailService();

        $user = $this->createNormalUser('user@example.com', 'password');

        $result = iterator_to_array($service->findByUser($user));

        $this->assertEmpty($result);
    }
}
