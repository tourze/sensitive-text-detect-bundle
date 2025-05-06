# SensitiveTextDetectBundle

[English](README.md) | [中文](README.zh-CN.md)

[![最新版本](https://img.shields.io/packagist/v/tourze/sensitive-text-detect-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/sensitive-text-detect-bundle)
[![构建状态](https://github.com/tourze/php-monorepo/workflows/PHPUnit%20Test/badge.svg)](https://github.com/tourze/php-monorepo/actions)
[![总下载量](https://img.shields.io/packagist/dt/tourze/sensitive-text-detect-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/sensitive-text-detect-bundle)

一个用于检测内容中敏感文本的 Symfony Bundle。

## 功能特性

- 与 Symfony 应用程序简单集成
- 通过服务容器实现可替换的检测器实现
- 支持带用户对象的上下文感知检测
- 基于 Symfony 的依赖注入和配置系统构建

## 安装

通过 Composer 安装：

```bash
composer require tourze/sensitive-text-detect-bundle
```

## 快速开始

### 在 Symfony 应用中注册 Bundle

```php
// config/bundles.php
return [
    // ...
    Tourze\SensitiveTextDetectBundle\SensitiveTextDetectBundle::class => ['all' => true],
];
```

### 注入并使用服务

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Tourze\SensitiveTextDetectBundle\Service\SensitiveTextDetector;

class TextCheckController extends AbstractController
{
    public function __construct(
        private readonly SensitiveTextDetector $sensitiveTextDetector,
    ) {
    }

    #[Route('/check-text', name: 'app_check_text')]
    public function checkText(): Response
    {
        $text = 'Sample text to check';
        $isSensitive = $this->sensitiveTextDetector->isSensitiveText($text);
        
        return $this->json([
            'is_sensitive' => $isSensitive,
        ]);
    }
}
```

## 自定义检测器

默认情况下，Bundle 使用 `DefaultTextSensitiveTextDetector` 实现，它总是返回 `false`。您可以通过实现 `SensitiveTextDetector` 接口并在容器中注册您的实现来自定义检测逻辑：

```php
<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\SensitiveTextDetectBundle\Service\SensitiveTextDetector;

#[AsAlias(SensitiveTextDetector::class)]
class CustomSensitiveTextDetector implements SensitiveTextDetector
{
    public function isSensitiveText(string $text, ?UserInterface $user = null): bool
    {
        // 实现自定义敏感文本检测逻辑
        return str_contains($text, '敏感词');
    }
}
```

## 测试

运行测试：

```bash
./vendor/bin/phpunit packages/sensitive-text-detect-bundle/tests
```

## 许可证

该包基于 MIT 许可证发布。详情请参阅 LICENSE 文件。 