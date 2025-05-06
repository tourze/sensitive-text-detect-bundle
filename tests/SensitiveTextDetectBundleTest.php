<?php

namespace Tourze\SensitiveTextDetectBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Tourze\SensitiveTextDetectBundle\SensitiveTextDetectBundle;

class SensitiveTextDetectBundleTest extends TestCase
{
    public function testBundleInitialization(): void
    {
        $bundle = new SensitiveTextDetectBundle();
        $this->assertInstanceOf(SensitiveTextDetectBundle::class, $bundle);
        $this->assertInstanceOf(BundleInterface::class, $bundle);
    }
    
    public function testBundleName(): void
    {
        $bundle = new SensitiveTextDetectBundle();
        $this->assertEquals('SensitiveTextDetectBundle', $bundle->getName());
    }
    
    public function testBundlePath(): void
    {
        $bundle = new SensitiveTextDetectBundle();
        $path = $bundle->getPath();
        $this->assertNotEmpty($path);
        $this->assertDirectoryExists($path);
    }
    
    public function testBundleNamespace(): void
    {
        $bundle = new SensitiveTextDetectBundle();
        $this->assertEquals('Tourze\SensitiveTextDetectBundle', $bundle->getNamespace());
    }
} 