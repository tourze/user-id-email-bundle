<?php

declare(strict_types=1);

namespace Tourze\UserIDEmailBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;

/**
 * 用户邮箱身份管理后台菜单提供者
 */
#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('用户管理')) {
            $item->addChild('用户管理');
        }

        $userMenu = $item->getChild('用户管理');
        if (null === $userMenu) {
            return;
        }

        // 添加邮箱身份管理子菜单
        if (null === $userMenu->getChild('身份管理')) {
            $userMenu->addChild('身份管理')
                ->setAttribute('icon', 'fas fa-id-card')
            ;
        }

        $identityMenu = $userMenu->getChild('身份管理');
        if (null === $identityMenu) {
            return;
        }

        $identityMenu->addChild('邮箱身份')
            ->setUri($this->linkGenerator->getCurdListPage(EmailIdentity::class))
            ->setAttribute('icon', 'fas fa-envelope')
        ;
    }
}
