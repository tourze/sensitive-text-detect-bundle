<?php

namespace Tourze\SensitiveTextDetectBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tourze\SensitiveTextDetectBundle\Service\DefaultTextSensitiveTextDetector;
use Tourze\SensitiveTextDetectBundle\Service\SensitiveTextDetector;

class SensitiveTextDetectIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return IntegrationTestKernel::class;
    }

    public function testServiceWiring(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        // 测试服务是否正确注册并可被获取
        $this->assertTrue($container->has(SensitiveTextDetector::class), 'SensitiveTextDetector 服务未正确注册');
        $this->assertTrue($container->has(DefaultTextSensitiveTextDetector::class), 'DefaultTextSensitiveTextDetector 服务未正确注册');

        $detector = $container->get(SensitiveTextDetector::class);
        $this->assertInstanceOf(SensitiveTextDetector::class, $detector, 'SensitiveTextDetector 服务类型不正确');
        $this->assertInstanceOf(DefaultTextSensitiveTextDetector::class, $detector, 'SensitiveTextDetector 应该指向 DefaultTextSensitiveTextDetector 实现');
    }

    public function testServiceFunctionality(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var SensitiveTextDetector $detector */
        $detector = $container->get(SensitiveTextDetector::class);

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
