<?php

namespace Tourze\SensitiveTextDetectBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;
use Tourze\SensitiveTextDetectBundle\SensitiveTextDetectBundle;
use Tourze\SensitiveTextDetectBundle\Service\DefaultTextSensitiveTextDetector;
use Tourze\SensitiveTextDetectBundle\Service\SensitiveTextDetector;

class IntegrationTestKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new SensitiveTextDetectBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', [
                'test' => true,
                'secret' => 'test_secret',
                'router' => [
                    'utf8' => true,
                    'resource' => 'kernel::loadRoutes',
                ],
                'uid' => [
                    'time_based_uuid_version' => 7,
                    'default_uuid_version' => 7,
                ],
            ]);

            // 手动注册服务以确保测试正常工作
            $container->setDefinition(DefaultTextSensitiveTextDetector::class,
                (new Definition(DefaultTextSensitiveTextDetector::class))
                    ->setPublic(true)
                    ->setAutowired(true)
                    ->setAutoconfigured(true)
            );

            // 注册接口别名
            $container->setAlias(SensitiveTextDetector::class, DefaultTextSensitiveTextDetector::class)
                ->setPublic(true);
        });
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/sensitive_text_detect_bundle/cache/' . $this->environment;
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/sensitive_text_detect_bundle/logs';
    }

    public function loadRoutes(): iterable
    {
        // 返回空路由集合，因为测试不需要实际路由
        return [];
    }
}
