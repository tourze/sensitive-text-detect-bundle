<?php

declare(strict_types=1);

namespace Tourze\SensitiveTextDetectBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use Tourze\SensitiveTextDetectBundle\SensitiveTextDetectBundle;

/**
 * @internal
 */
#[CoversClass(SensitiveTextDetectBundle::class)]
#[RunTestsInSeparateProcesses]
final class SensitiveTextDetectBundleTest extends AbstractBundleTestCase
{
}
