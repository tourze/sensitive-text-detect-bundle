# SensitiveTextDetectBundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/sensitive-text-detect-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/sensitive-text-detect-bundle)
[![Build Status](https://github.com/tourze/php-monorepo/workflows/PHPUnit%20Test/badge.svg)](https://github.com/tourze/php-monorepo/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/sensitive-text-detect-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/sensitive-text-detect-bundle)

A Symfony bundle for detecting sensitive text in content.

## Features

- Simple integration with Symfony applications
- Replaceable detector implementation through the service container
- Support for context-aware detection with user object support
- Built on Symfony's dependency injection and configuration system

## Installation

Install via Composer:

```bash
composer require tourze/sensitive-text-detect-bundle
```

## Quick Start

### Register the Bundle in your Symfony application

```php
// config/bundles.php
return [
    // ...
    Tourze\SensitiveTextDetectBundle\SensitiveTextDetectBundle::class => ['all' => true],
];
```

### Inject and use the service

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

## Customizing the Detector

By default, the bundle uses `DefaultTextSensitiveTextDetector` implementation, which always returns `false`. You can customize the detection logic by implementing the `SensitiveTextDetector` interface and registering your implementation in the container:

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
        // Implement your custom sensitive text detection logic
        return str_contains($text, 'sensitive-word');
    }
}
```

## Testing

Run tests:

```bash
./vendor/bin/phpunit packages/sensitive-text-detect-bundle/tests
```

## License

This package is released under the MIT License. See the LICENSE file for details.
