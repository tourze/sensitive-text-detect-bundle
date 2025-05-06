<?php

namespace Tourze\SensitiveTextDetectBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\SensitiveTextDetectBundle\DependencyInjection\SensitiveTextDetectExtension;
use Tourze\SensitiveTextDetectBundle\Service\DefaultTextSensitiveTextDetector;

class SensitiveTextDetectExtensionTest extends TestCase
{
    private SensitiveTextDetectExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new SensitiveTextDetectExtension();
        $this->container = new ContainerBuilder();
    }

    public function testLoadExtension(): void
    {
        $this->extension->load([], $this->container);

        // 验证服务是否被正确注册
        $this->assertTrue($this->container->hasDefinition(DefaultTextSensitiveTextDetector::class), 'DefaultTextSensitiveTextDetector 服务未注册');
    }

    public function testExtensionAlias(): void
    {
        $this->assertEquals('sensitive_text_detect', $this->extension->getAlias());
    }

    public function testServiceAutowireConfiguration(): void
    {
        $this->extension->load([], $this->container);

        $definition = $this->container->getDefinition(DefaultTextSensitiveTextDetector::class);
        $this->assertTrue($definition->isAutowired(), 'DefaultTextSensitiveTextDetector 服务未配置自动装配');
        $this->assertTrue($definition->isAutoconfigured(), 'DefaultTextSensitiveTextDetector 服务未配置自动配置');
    }

    public function testServiceTagging(): void
    {
        $this->extension->load([], $this->container);

        // 确保所有服务都正确加载
        $serviceIds = $this->container->getServiceIds();
        $filtered = array_filter($serviceIds, function ($id) {
            return strpos($id, 'Tourze\\SensitiveTextDetectBundle\\Service\\') === 0;
        });

        $this->assertNotEmpty($filtered, '没有找到任何 Service 服务');
    }
}
