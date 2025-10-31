<?php

declare(strict_types=1);

namespace Tourze\UserIDEmailBundle\Tests\Service;

use Knp\Menu\MenuFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use Tourze\UserIDEmailBundle\Service\AdminMenu;

/**
 * AdminMenu服务测试
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    protected function onSetUp(): void
    {
        // Setup for AdminMenu tests
    }

    public function testInvokeAddsMenuItems(): void
    {
        $container = self::getContainer();
        /** @var AdminMenu $adminMenu */
        $adminMenu = $container->get(AdminMenu::class);

        $factory = new MenuFactory();
        $rootItem = $factory->createItem('root');

        $adminMenu->__invoke($rootItem);

        // 验证菜单结构
        $userMenu = $rootItem->getChild('用户管理');
        self::assertNotNull($userMenu, '用户管理菜单应该存在');

        $identityMenu = $userMenu->getChild('身份管理');
        self::assertNotNull($identityMenu, '身份管理菜单应该存在');

        $emailIdentityItem = $identityMenu->getChild('邮箱身份');
        self::assertNotNull($emailIdentityItem, '邮箱身份菜单项应该存在');

        // 验证菜单项的属性
        self::assertEquals('fas fa-id-card', $identityMenu->getAttribute('icon'));
        self::assertEquals('fas fa-envelope', $emailIdentityItem->getAttribute('icon'));
    }

    public function testInvokeHandlesExistingUserMenu(): void
    {
        $container = self::getContainer();
        /** @var AdminMenu $adminMenu */
        $adminMenu = $container->get(AdminMenu::class);

        $factory = new MenuFactory();
        $rootItem = $factory->createItem('root');

        // 预先添加用户管理菜单
        $rootItem->addChild('用户管理');

        $adminMenu->__invoke($rootItem);

        // 验证菜单结构仍然正确
        $userMenu = $rootItem->getChild('用户管理');
        self::assertNotNull($userMenu);

        $identityMenu = $userMenu->getChild('身份管理');
        self::assertNotNull($identityMenu);

        $emailIdentityItem = $identityMenu->getChild('邮箱身份');
        self::assertNotNull($emailIdentityItem);
    }
}
