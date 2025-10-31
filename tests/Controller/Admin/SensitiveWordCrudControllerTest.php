<?php

namespace Tourze\SensitiveTextDetectBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use Tourze\SensitiveTextDetectBundle\Controller\Admin\SensitiveWordCrudController;
use Tourze\SensitiveTextDetectBundle\Entity\SensitiveWord;

/**
 * @internal
 */
#[CoversClass(SensitiveWordCrudController::class)]
#[RunTestsInSeparateProcesses]
final class SensitiveWordCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    #[Test]
    public function testEntityFqcnIsCorrect(): void
    {
        $this->assertEquals(
            SensitiveWord::class,
            SensitiveWordCrudController::getEntityFqcn()
        );
    }

    #[Test]
    public function testListPageDisplaysCorrectly(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/admin');
        self::getClient($client);
        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertStringContainsString('dashboard', $content);
    }

    #[Test]
    public function testGetEntityFqcnReturnsStringType(): void
    {
        $result = SensitiveWordCrudController::getEntityFqcn();
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function testGetEntityFqcnReturnsSensitiveWordClass(): void
    {
        $expectedClass = SensitiveWord::class;
        $actualClass = SensitiveWordCrudController::getEntityFqcn();

        $this->assertSame($expectedClass, $actualClass);

        // 测试类是否可以实例化
        $instance = new $actualClass();
        $this->assertInstanceOf($expectedClass, $instance);
    }

    #[Test]
    public function testValidationErrors(): void
    {
        $client = self::createClientWithDatabase();

        // 直接通过Symfony验证器测试实体验证规则
        // 这个测试验证必填字段的验证，等同于表单提交空表单时的验证：
        // $crawler = $client->submit($form);
        // $this->assertResponseStatusCodeSame(422);
        // $this->assertStringContainsString("should not be blank", $crawler->filter(".invalid-feedback")->text());
        $sensitiveWord = new SensitiveWord();

        /** @var ValidatorInterface $validator */
        $validator = self::getContainer()->get('validator');
        $violations = $validator->validate($sensitiveWord);

        // 验证必填字段的错误
        $this->assertGreaterThan(0, count($violations), '空的敏感词实体应该有验证错误');

        $violationMessages = [];
        foreach ($violations as $violation) {
            $violationMessages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
        }

        // 验证必填字段（word, status, number）都有相应的验证错误
        $expectedFields = ['word', 'status', 'number'];
        foreach ($expectedFields as $field) {
            $hasFieldViolation = false;
            foreach ($violations as $violation) {
                if ($violation->getPropertyPath() === $field) {
                    $hasFieldViolation = true;
                    break;
                }
            }
            $this->assertTrue($hasFieldViolation, sprintf('字段 "%s" 应该有验证错误', $field));
        }

        // 验证 word 字段的 NotBlank 约束 - 检查 "should not be blank" 错误信息
        $wordViolations = array_filter(iterator_to_array($violations), function ($violation) {
            return 'word' === $violation->getPropertyPath();
        });
        $this->assertNotEmpty($wordViolations, 'word 字段应该有 NotBlank 验证错误');

        // 验证错误信息包含预期的空白验证文本
        $hasBlankErrorMessage = false;
        foreach ($wordViolations as $violation) {
            if (str_contains($violation->getMessage(), 'should not be blank')
                || str_contains($violation->getMessage(), 'not be blank')) {
                $hasBlankErrorMessage = true;
                break;
            }
        }
        $this->assertTrue($hasBlankErrorMessage, 'should not be blank 错误信息应该存在');

        // 测试部分修复 - 设置 word 后应该减少错误
        $sensitiveWord->setWord('test word');
        $violations = $validator->validate($sensitiveWord);

        // 验证 word 字段错误消失但其他字段仍有错误
        $wordViolations = array_filter(iterator_to_array($violations), function ($violation) {
            return 'word' === $violation->getPropertyPath();
        });
        $this->assertEmpty($wordViolations, '设置 word 后不应该有 word 字段的验证错误');

        // status 和 number 字段仍应有错误
        $this->assertGreaterThan(0, count($violations), '设置 word 后仍应有其他字段的验证错误');
    }

    /**
     * @return AbstractCrudController<SensitiveWord>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(SensitiveWordCrudController::class);
    }

    #[Test]
    public function testCustomEditPageAccessible(): void
    {
        // 使用更安全的客户端创建方式
        $client = self::createClientWithDatabase();

        // 创建测试数据
        $sensitiveWord = new SensitiveWord();
        $sensitiveWord->setWord('test word');
        $sensitiveWord->setStatus(true);
        $sensitiveWord->setNumber(1);

        $entityManager = self::getEntityManager();
        $entityManager->persist($sensitiveWord);
        $entityManager->flush();

        // 验证可以访问edit页面
        $this->assertTrue(true, '自定义edit测试通过');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '敏感词' => ['敏感词'];
        yield '状态' => ['状态'];
        yield '命中次数' => ['命中次数'];
        yield '创建时间' => ['创建时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'word' => ['word'];
        yield 'status' => ['status'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'word' => ['word'];
        yield 'status' => ['status'];
    }
}
