<?php

namespace Tourze\SensitiveTextDetectBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\SensitiveTextDetectBundle\Service\SensitiveTextDetector;

/**
 * @internal
 */
#[CoversClass(SensitiveTextDetector::class)]
final class SensitiveTextDetectorTest extends TestCase
{
    public function testInterfaceExists(): void
    {
        self::assertTrue(interface_exists(SensitiveTextDetector::class));
    }

    public function testInterfaceHasRequiredMethods(): void
    {
        $reflection = new \ReflectionClass(SensitiveTextDetector::class);
        self::assertTrue($reflection->hasMethod('isSensitiveText'));

        $method = $reflection->getMethod('isSensitiveText');
        self::assertCount(2, $method->getParameters());

        $parameters = $method->getParameters();
        self::assertSame('text', $parameters[0]->getName());
        $textType = $parameters[0]->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $textType);
        self::assertSame('string', $textType->getName());

        self::assertSame('user', $parameters[1]->getName());
        self::assertTrue($parameters[1]->allowsNull());
    }
}
