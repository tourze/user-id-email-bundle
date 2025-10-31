<?php

namespace Tourze\UserIDEmailBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\UserIDBundle\Model\Identity;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;

/**
 * @internal
 */
#[CoversClass(EmailIdentity::class)]
final class EmailIdentityTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new EmailIdentity();
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'emailAddress' => ['emailAddress', 'test_value'],
        ];
    }

    private EmailIdentity $emailIdentity;

    protected function setUp(): void
    {
        parent::setUp();

        $this->emailIdentity = new EmailIdentity();
    }

    public function testGetIdWithDefault(): void
    {
        $this->assertNull($this->emailIdentity->getId());
    }

    public function testGetSetEmailAddressWithValidData(): void
    {
        $email = 'test@example.com';
        $this->emailIdentity->setEmailAddress($email);
        $this->assertSame($email, $this->emailIdentity->getEmailAddress());
    }

    public function testGetSetEmailAddressWithEmptyString(): void
    {
        $this->emailIdentity->setEmailAddress('');
        $this->assertSame('', $this->emailIdentity->getEmailAddress());
    }

    public function testGetSetEmailAddressWithSpecialCharacters(): void
    {
        $email = 'test+special.chars@example-domain.com';
        $this->emailIdentity->setEmailAddress($email);
        $this->assertSame($email, $this->emailIdentity->getEmailAddress());
    }

    public function testGetSetUserWithValidUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $this->emailIdentity->setUser($user);
        $this->assertSame($user, $this->emailIdentity->getUser());
    }

    public function testGetSetUserWithNull(): void
    {
        $this->emailIdentity->setUser(null);
        $this->assertNull($this->emailIdentity->getUser());
    }

    public function testGetSetCreatedByWithValidData(): void
    {
        $createdBy = 'user123';
        $this->emailIdentity->setCreatedBy($createdBy);
        $this->assertSame($createdBy, $this->emailIdentity->getCreatedBy());
    }

    public function testGetSetCreatedByWithNull(): void
    {
        $this->emailIdentity->setCreatedBy(null);
        $this->assertNull($this->emailIdentity->getCreatedBy());
    }

    public function testGetSetUpdatedByWithValidData(): void
    {
        $updatedBy = 'user456';
        $this->emailIdentity->setUpdatedBy($updatedBy);
        $this->assertSame($updatedBy, $this->emailIdentity->getUpdatedBy());
    }

    public function testGetSetUpdatedByWithNull(): void
    {
        $this->emailIdentity->setUpdatedBy(null);
        $this->assertNull($this->emailIdentity->getUpdatedBy());
    }

    public function testGetSetCreateTimeWithValidDateTime(): void
    {
        $dateTime = new \DateTimeImmutable();
        $this->emailIdentity->setCreateTime($dateTime);
        $this->assertSame($dateTime, $this->emailIdentity->getCreateTime());
    }

    public function testGetSetCreateTimeWithNull(): void
    {
        $this->emailIdentity->setCreateTime(null);
        $this->assertNull($this->emailIdentity->getCreateTime());
    }

    public function testGetSetUpdateTimeWithValidDateTime(): void
    {
        $dateTime = new \DateTimeImmutable();
        $this->emailIdentity->setUpdateTime($dateTime);
        $this->assertSame($dateTime, $this->emailIdentity->getUpdateTime());
    }

    public function testGetSetUpdateTimeWithNull(): void
    {
        $this->emailIdentity->setUpdateTime(null);
        $this->assertNull($this->emailIdentity->getUpdateTime());
    }

    public function testGetIdentityValueReturnsEmailAddress(): void
    {
        $email = 'test@example.com';
        $this->emailIdentity->setEmailAddress($email);
        $this->assertSame($email, $this->emailIdentity->getIdentityValue());
    }

    public function testGetIdentityTypeReturnsEmailType(): void
    {
        $this->assertSame(EmailIdentity::IDENTITY_TYPE, $this->emailIdentity->getIdentityType());
        $this->assertSame('email', $this->emailIdentity->getIdentityType());
    }

    public function testGetIdentityArrayWithValidData(): void
    {
        // 设置必要的值
        $id = '123456789';
        $email = 'test@example.com';
        $dateTime = new \DateTimeImmutable('2023-01-01 12:00:00');

        // 使用反射设置私有ID属性
        $reflectionProperty = new \ReflectionProperty(EmailIdentity::class, 'id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->emailIdentity, $id);

        $this->emailIdentity->setEmailAddress($email);
        $this->emailIdentity->setCreateTime($dateTime);
        $this->emailIdentity->setUpdateTime($dateTime);

        // 获取结果并转换为数组以便于断言
        $identities = iterator_to_array($this->emailIdentity->getIdentityArray());

        $this->assertCount(1, $identities);
        $identity = $identities[0];

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertSame($id, $identity->getId());
        $this->assertSame(EmailIdentity::IDENTITY_TYPE, $identity->getIdentityType());
        $this->assertSame($email, $identity->getIdentityValue());

        $metadata = $identity->getExtra();
        $this->assertArrayHasKey('createTime', $metadata);
        $this->assertArrayHasKey('updateTime', $metadata);
        $this->assertSame($dateTime->format('Y-m-d H:i:s'), $metadata['createTime']);
        $this->assertSame($dateTime->format('Y-m-d H:i:s'), $metadata['updateTime']);
    }

    public function testGetAccountsReturnsEmptyArray(): void
    {
        $this->assertSame([], $this->emailIdentity->getAccounts());
    }
}
