<?php

namespace Tourze\SensitiveTextDetectBundle\Service;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * 内容安全服务
 */
interface SensitiveTextDetector
{
    /**
     * 检查是否是敏感文本
     */
    public function isSensitiveText(string $text, ?UserInterface $user = null): bool;
}
