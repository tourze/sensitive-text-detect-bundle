<?php

namespace Tourze\SensitiveTextDetectBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use Tourze\SensitiveTextDetectBundle\DependencyInjection\SensitiveTextDetectExtension;

/**
 * @internal
 */
#[CoversClass(SensitiveTextDetectExtension::class)]
final class SensitiveTextDetectExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    public function testGetConfigDir(): void
    {
        $extension = new SensitiveTextDetectExtension();
        $reflection = new \ReflectionClass($extension);
        $method = $reflection->getMethod('getConfigDir');
        $method->setAccessible(true);

        $configDir = $method->invoke($extension);
        $expectedPath = realpath(__DIR__ . '/../../src/Resources/config');
        $actualPath = realpath($configDir);

        $this->assertSame($expectedPath, $actualPath);
        $this->assertDirectoryExists($configDir, '配置目录不存在');
    }

    public function testConfigFileExists(): void
    {
        $extension = new SensitiveTextDetectExtension();
        $reflection = new \ReflectionClass($extension);
        $method = $reflection->getMethod('getConfigDir');
        $method->setAccessible(true);

        $configDir = $method->invoke($extension);
        $servicesFile = $configDir . '/services.yaml';

        $this->assertFileExists($servicesFile, 'services.yaml 文件不存在');
    }

    public function testExtensionAlias(): void
    {
        $extension = new SensitiveTextDetectExtension();
        $alias = $extension->getAlias();

        $this->assertSame('sensitive_text_detect', $alias);
    }
}
