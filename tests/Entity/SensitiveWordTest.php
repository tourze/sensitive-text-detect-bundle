<?php

declare(strict_types=1);

namespace Tourze\SensitiveTextDetectBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\SensitiveTextDetectBundle\Entity\SensitiveWord;

/**
 * 敏感词实体测试
 *
 * @internal
 */
#[CoversClass(SensitiveWord::class)]
final class SensitiveWordTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new SensitiveWord();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'word' => ['word', '测试敏感词'];
        yield 'number' => ['number', 5];
    }

    public function testSensitiveWordShouldBeInstantiable(): void
    {
        $word = new SensitiveWord();

        $this->assertInstanceOf(SensitiveWord::class, $word);
    }

    public function testToStringShouldReturnStringRepresentation(): void
    {
        $word = new SensitiveWord();

        $result = (string) $word;

        $this->assertNotEmpty($result);
    }

    public function testBasicPropertiesShouldWork(): void
    {
        $word = new SensitiveWord();

        $this->assertNotNull($word);
        $this->assertNull($word->getId());
    }
}
