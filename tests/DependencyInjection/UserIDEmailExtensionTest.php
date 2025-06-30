<?php

namespace Tourze\UserIDEmailBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\UserIDEmailBundle\DependencyInjection\UserIDEmailExtension;

class UserIDEmailExtensionTest extends TestCase
{
    private UserIDEmailExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new UserIDEmailExtension();
        $this->container = new ContainerBuilder();
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