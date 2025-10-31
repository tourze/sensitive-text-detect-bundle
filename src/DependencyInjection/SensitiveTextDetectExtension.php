<?php

namespace Tourze\SensitiveTextDetectBundle\DependencyInjection;

use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

class SensitiveTextDetectExtension extends AutoExtension
{
    protected function getConfigDir(): string
    {
        return __DIR__ . '/../Resources/config';
    }
}
