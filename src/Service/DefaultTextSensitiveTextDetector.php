<?php

namespace Tourze\SensitiveTextDetectBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * 内容安全服务
 */
#[AsAlias(SensitiveTextDetector::class)]
class DefaultTextSensitiveTextDetector implements SensitiveTextDetector
{
    /**
     * 检查是否是敏感文本
     */
    public function isSensitiveText(string $text, ?UserInterface $user = null): bool
    {
        return false;
    }
}
