<?php

namespace Tourze\UserIDEmailBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use Tourze\UserIDEmailBundle\DependencyInjection\UserIDEmailExtension;

/**
 * @internal
 */
#[CoversClass(UserIDEmailExtension::class)]
final class UserIDEmailExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private UserIDEmailExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension = new UserIDEmailExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    protected function getExtension(): UserIDEmailExtension
    {
        return $this->extension;
    }

    protected function getContainer(): ContainerBuilder
    {
        return $this->container;
    }

    public function testLoad(): void
    {
        $this->extension->load([], $this->container);

        $this->assertTrue(
            $this->container->hasDefinition('Tourze\UserIDEmailBundle\Repository\EmailIdentityRepository'),
            'The email identity repository service should be registered'
        );

        $this->assertTrue(
            $this->container->hasDefinition('Tourze\UserIDEmailBundle\Service\UserIdentityEmailService'),
            'The user identity email service should be registered'
        );
    }
}
