<?php

namespace Tourze\SensitiveTextDetectBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\SensitiveTextDetectBundle\Service\DefaultTextSensitiveTextDetector;
use Tourze\SensitiveTextDetectBundle\Service\SensitiveTextDetector;

/**
 * @internal
 */
#[CoversClass(DefaultTextSensitiveTextDetector::class)]
#[RunTestsInSeparateProcesses]
final class DefaultTextSensitiveTextDetectorTest extends AbstractIntegrationTestCase
{
    private SensitiveTextDetector $detector;

    protected function onSetUp(): void
    {
        $this->detector = self::getService(SensitiveTextDetector::class);
    }

    private function getDetector(): SensitiveTextDetector
    {
        return $this->detector;
    }

    public function testIsSensitiveTextWithEmptyString(): void
    {
        $result = $this->getDetector()->isSensitiveText('', null);
        $this->assertFalse($result);
    }

    public function testIsSensitiveTextWithNormalText(): void
    {
        $result = $this->getDetector()->isSensitiveText('This is a normal text', null);
        $this->assertFalse($result);
    }

    public function testIsSensitiveTextWithSpecialCharacters(): void
    {
        $result = $this->getDetector()->isSensitiveText('!@#$%^&*()_+', null);
        $this->assertFalse($result);
    }

    public function testIsSensitiveTextWithLongText(): void
    {
        $longText = str_repeat('Long text content. ', 100);
        $result = $this->getDetector()->isSensitiveText($longText, null);
        $this->assertFalse($result);
    }

    public function testIsSensitiveTextWithUserObject(): void
    {
        $user = self::createStub(UserInterface::class);
        $user->method('getUserIdentifier')->willReturn('test_user');
        $user->method('getRoles')->willReturn(['ROLE_USER']);

        $result = $this->getDetector()->isSensitiveText('Some text', $user);
        $this->assertFalse($result);
    }

    public function testIsSensitiveTextWithNullableUserAndText(): void
    {
        $user = null;
        $result = $this->getDetector()->isSensitiveText('Some text', $user);
        $this->assertFalse($result);
    }

    public function testIsSensitiveTextWithMultibyteCharacters(): void
    {
        $multibyteText = '这是中文文本，包含多字节字符';
        $result = $this->getDetector()->isSensitiveText($multibyteText, null);
        $this->assertFalse($result);
    }
}
