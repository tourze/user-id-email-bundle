<?php

namespace Tourze\UserIDEmailBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tourze\UserIDEmailBundle\UserIDEmailBundle;

class UserIDEmailBundleTest extends TestCase
{
    public function testBundleInstantiation(): void
    {
        $bundle = new UserIDEmailBundle();
        $this->assertInstanceOf(UserIDEmailBundle::class, $bundle);
    }
}