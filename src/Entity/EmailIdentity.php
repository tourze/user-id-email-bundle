<?php

namespace Tourze\UserIDEmailBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\UserIDBundle\Contracts\IdentityInterface;
use Tourze\UserIDBundle\Model\Identity;
use Tourze\UserIDEmailBundle\Repository\EmailIdentityRepository;

#[ORM\Entity(repositoryClass: EmailIdentityRepository::class)]
#[ORM\Table(name: 'ims_user_identity_email', options: ['comment' => '用户身份-电子邮箱'])]
class EmailIdentity implements IdentityInterface, \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    public const IDENTITY_TYPE = 'email';


    #[ORM\Column(type: Types::STRING, length: 255, nullable: false, options: ['comment' => '邮箱地址'])]
    private string $emailAddress;

    #[ORM\ManyToOne]
    private ?UserInterface $user = null;


    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): static
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getIdentityValue(): string
    {
        return $this->getEmailAddress();
    }

    public function getIdentityType(): string
    {
        return self::IDENTITY_TYPE;
    }

    public function getIdentityArray(): \Traversable
    {
        yield new Identity($this->getId(), $this->getIdentityType(), $this->getIdentityValue(), [
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
        ]);
    }

    public function getAccounts(): array
    {
        return [];
    }

    public function __toString(): string
    {
        return $this->getEmailAddress();
    }
}
