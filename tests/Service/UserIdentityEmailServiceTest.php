<?php

namespace Tourze\UserIDEmailBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserIDBundle\Contracts\IdentityInterface;
use Tourze\UserIDBundle\Service\UserIdentityService;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;
use Tourze\UserIDEmailBundle\Repository\EmailIdentityRepository;
use Tourze\UserIDEmailBundle\Service\UserIdentityEmailService;

class UserIdentityEmailServiceTest extends TestCase
{
    private EmailIdentityRepository $emailIdentityRepository;
    private UserIdentityService $innerService;
    private UserIdentityEmailService $service;

    protected function setUp(): void
    {
        $this->emailIdentityRepository = $this->createMock(EmailIdentityRepository::class);
        $this->innerService = $this->createMock(UserIdentityService::class);
        $this->service = new UserIdentityEmailService(
            $this->emailIdentityRepository,
            $this->innerService
        );
    }

    public function testFindByType_withEmailTypeAndExistingValue(): void
    {
        $emailAddress = 'test@example.com';
        $emailIdentity = $this->createMock(EmailIdentity::class);

        $this->emailIdentityRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['emailAddress' => $emailAddress])
            ->willReturn($emailIdentity);

        $this->innerService->expects($this->never())
            ->method('findByType');

        $result = $this->service->findByType(EmailIdentity::IDENTITY_TYPE, $emailAddress);
        
        $this->assertSame($emailIdentity, $result);
    }

    public function testFindByType_withEmailTypeAndNonExistingValue(): void
    {
        $emailAddress = 'nonexistent@example.com';

        $this->emailIdentityRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['emailAddress' => $emailAddress])
            ->willReturn(null);

        $this->innerService->expects($this->once())
            ->method('findByType')
            ->with(EmailIdentity::IDENTITY_TYPE, $emailAddress)
            ->willReturn(null);

        $result = $this->service->findByType(EmailIdentity::IDENTITY_TYPE, $emailAddress);
        
        $this->assertNull($result);
    }

    public function testFindByType_withNonEmailType(): void
    {
        $type = 'phone';
        $value = '1234567890';
        $identityMock = $this->createMock(IdentityInterface::class);

        $this->emailIdentityRepository->expects($this->never())
            ->method('findOneBy');

        $this->innerService->expects($this->once())
            ->method('findByType')
            ->with($type, $value)
            ->willReturn($identityMock);

        $result = $this->service->findByType($type, $value);
        
        $this->assertSame($identityMock, $result);
    }

    public function testFindByUser_withUserHavingEmailIdentities(): void
    {
        $user = $this->createMock(UserInterface::class);
        $emailIdentity1 = $this->createMock(EmailIdentity::class);
        $emailIdentity2 = $this->createMock(EmailIdentity::class);
        $otherIdentity = $this->createMock(IdentityInterface::class);
        
        $this->emailIdentityRepository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([$emailIdentity1, $emailIdentity2]);
            
        $this->innerService->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([$otherIdentity]);
            
        $result = iterator_to_array($this->service->findByUser($user));
        
        $this->assertCount(3, $result);
        $this->assertSame($emailIdentity1, $result[0]);
        $this->assertSame($emailIdentity2, $result[1]);
        $this->assertSame($otherIdentity, $result[2]);
    }

    public function testFindByUser_withUserHavingNoEmailIdentities(): void
    {
        $user = $this->createMock(UserInterface::class);
        $otherIdentity = $this->createMock(IdentityInterface::class);
        
        $this->emailIdentityRepository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([]);
            
        $this->innerService->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([$otherIdentity]);
            
        $result = iterator_to_array($this->service->findByUser($user));
        
        $this->assertCount(1, $result);
        $this->assertSame($otherIdentity, $result[0]);
    }

    public function testFindByUser_withUserHavingNoIdentities(): void
    {
        $user = $this->createMock(UserInterface::class);
        
        $this->emailIdentityRepository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([]);
            
        $this->innerService->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([]);
            
        $result = iterator_to_array($this->service->findByUser($user));
        
        $this->assertCount(0, $result);
    }
} 