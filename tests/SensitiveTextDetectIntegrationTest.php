<?php

namespace Tourze\SensitiveTextDetectBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\SensitiveTextDetectBundle\SensitiveTextDetectBundle;
use Tourze\SensitiveTextDetectBundle\Service\DefaultTextSensitiveTextDetector;
use Tourze\SensitiveTextDetectBundle\Service\SensitiveTextDetector;

/**
 * @internal
 */
#[CoversClass(SensitiveTextDetectBundle::class)]
#[RunTestsInSeparateProcesses]
final class SensitiveTextDetectIntegrationTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 集成测试设置
    }

    public function testServiceWiring(): void
    {
        // 测试服务是否正确注册并可被获取
        $detector = self::getService(SensitiveTextDetector::class);
        $this->assertInstanceOf(SensitiveTextDetector::class, $detector, 'SensitiveTextDetector 服务类型不正确');
        $this->assertInstanceOf(DefaultTextSensitiveTextDetector::class, $detector, 'SensitiveTextDetector 应该指向 DefaultTextSensitiveTextDetector 实现');

        $defaultDetector = self::getService(DefaultTextSensitiveTextDetector::class);
        $this->assertInstanceOf(DefaultTextSensitiveTextDetector::class, $defaultDetector, 'DefaultTextSensitiveTextDetector 服务未正确注册');
    }

    public function testServiceFunctionality(): void
    {
        $detector = self::getService(SensitiveTextDetector::class);
        $this->assertInstanceOf(SensitiveTextDetector::class, $detector);

        // 测试服务基本功能
        $result = $detector->isSensitiveText('test text', null);
        $this->assertFalse($result, 'DefaultTextSensitiveTextDetector 的实现应该总是返回 false');

        // 测试不同输入的情况
        $result = $detector->isSensitiveText('', null);
        $this->assertFalse($result, '空字符串应该返回 false');

        $result = $detector->isSensitiveText('敏感文本测试', null);
        $this->assertFalse($result, '包含多字节字符的文本应该返回 false');
    }
}
