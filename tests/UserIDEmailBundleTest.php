<?php

declare(strict_types=1);

namespace UserIdEmailBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use Tourze\UserIDEmailBundle\UserIDEmailBundle;

/**
 * @internal
 */
#[CoversClass(UserIDEmailBundle::class)]
#[RunTestsInSeparateProcesses]
final class UserIDEmailBundleTest extends AbstractBundleTestCase
{
}
