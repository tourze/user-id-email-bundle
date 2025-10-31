<?php

namespace Tourze\UserIDEmailBundle\DependencyInjection;

use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

class UserIDEmailExtension extends AutoExtension
{
    protected function getConfigDir(): string
    {
        return __DIR__ . '/../Resources/config';
    }
}
