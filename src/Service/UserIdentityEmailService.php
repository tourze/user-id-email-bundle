<?php

namespace Tourze\UserIDEmailBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserIDBundle\Contracts\UserIdentityInterface;
use Tourze\UserIDBundle\Service\UserIdentityService;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;
use Tourze\UserIDEmailBundle\Repository\EmailIdentityRepository;

#[AsDecorator(decorates: UserIdentityService::class)]
class UserIdentityEmailService implements UserIdentityService
{
    public function __construct(
        private readonly EmailIdentityRepository $emailIdentityRepository,
        #[AutowireDecorated] private readonly UserIdentityService $inner,
    ) {
    }

    public function findByType(string $type, string $value): ?UserIdentityInterface
    {
        // 邮箱
        if (EmailIdentity::IDENTITY_TYPE === $type) {
            $result = $this->emailIdentityRepository?->findOneBy(['emailAddress' => $value]);
            if ($result) {
                return $result;
            }
        }

        return $this->inner->findByType($type, $value);
    }

    public function findByUser(UserInterface $user): iterable
    {
        foreach ($this->emailIdentityRepository->findBy(['user' => $user]) as $item) {
            yield $item;
        }
        foreach ($this->inner->findByUser($user) as $item) {
            yield $item;
        }
    }
}
