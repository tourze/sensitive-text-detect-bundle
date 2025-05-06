<?php

namespace Tourze\SensitiveTextDetectBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\SensitiveTextDetectBundle\Service\DefaultTextSensitiveTextDetector;

class DefaultTextSensitiveTextDetectorTest extends TestCase
{
    private DefaultTextSensitiveTextDetector $detector;

    protected function setUp(): void
    {
        $this->detector = new DefaultTextSensitiveTextDetector();
    }

    public function testIsSensitiveText_withEmptyString(): void
    {
        $result = $this->detector->isSensitiveText('', null);
        $this->assertFalse($result);
    }

    public function testIsSensitiveText_withNormalText(): void
    {
        $result = $this->detector->isSensitiveText('This is a normal text', null);
        $this->assertFalse($result);
    }

    public function testIsSensitiveText_withSpecialCharacters(): void
    {
        $result = $this->detector->isSensitiveText('!@#$%^&*()_+', null);
        $this->assertFalse($result);
    }

    public function testIsSensitiveText_withLongText(): void
    {
        $longText = str_repeat('Long text content. ', 100);
        $result = $this->detector->isSensitiveText($longText, null);
        $this->assertFalse($result);
    }

    public function testIsSensitiveText_withUserObject(): void
    {
        $user = $this->createMock(UserInterface::class);
        $result = $this->detector->isSensitiveText('Some text', $user);
        $this->assertFalse($result);
    }

    public function testIsSensitiveText_withNullableUserAndText(): void
    {
        $user = null;
        $result = $this->detector->isSensitiveText('Some text', $user);
        $this->assertFalse($result);
    }

    public function testIsSensitiveText_withMultibyteCharacters(): void
    {
        $multibyteText = '这是中文文本，包含多字节字符';
        $result = $this->detector->isSensitiveText($multibyteText, null);
        $this->assertFalse($result);
    }
}
