<?php

declare(strict_types=1);

namespace Tourze\SensitiveTextDetectBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use Tourze\SensitiveTextDetectBundle\Entity\SensitiveWord;
use Tourze\SensitiveTextDetectBundle\Repository\SensitiveWordRepository;

/**
 * @internal
 */
#[CoversClass(SensitiveWordRepository::class)]
#[RunTestsInSeparateProcesses]
final class SensitiveWordRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 没有特殊设置需求
    }

    /**
     * @return ServiceEntityRepository<SensitiveWord>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(SensitiveWordRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new SensitiveWord();
        $entity->setWord('test_word_' . uniqid());
        $entity->setStatus(true);
        $entity->setNumber(0);

        return $entity;
    }

    public function testFindOneByWithOrderByClause(): void
    {
        $entity1 = new SensitiveWord();
        $entity1->setWord('B Enabled Word');
        $entity1->setStatus(true);
        $entity1->setNumber(0);

        $entity2 = new SensitiveWord();
        $entity2->setWord('A Enabled Word');
        $entity2->setStatus(true);
        $entity2->setNumber(0);

        $entityManager = self::getService(EntityManagerInterface::class);
        $entityManager->persist($entity1);
        $entityManager->persist($entity2);
        $entityManager->flush();

        $repository = self::getService(SensitiveWordRepository::class);
        $result = $repository->findOneBy(['status' => true], ['word' => 'ASC']);

        $this->assertNotNull($result);
        $this->assertEquals('A Enabled Word', $result->getWord());
    }

    public function testFindByWithNumberCriteriaShouldWork(): void
    {
        $entity1 = new SensitiveWord();
        $entity1->setWord('Word with number 0');
        $entity1->setStatus(true);
        $entity1->setNumber(0);

        $entity2 = new SensitiveWord();
        $entity2->setWord('Word with number 5');
        $entity2->setStatus(true);
        $entity2->setNumber(5);

        $entity3 = new SensitiveWord();
        $entity3->setWord('Another word with number 0');
        $entity3->setStatus(true);
        $entity3->setNumber(0);

        $entityManager = self::getService(EntityManagerInterface::class);
        $entityManager->persist($entity1);
        $entityManager->persist($entity2);
        $entityManager->persist($entity3);
        $entityManager->flush();

        $repository = self::getService(SensitiveWordRepository::class);
        $results = $repository->findBy(['number' => 0]);

        $this->assertCount(2, $results);
        foreach ($results as $result) {
            $this->assertEquals(0, $result->getNumber());
        }
    }
}
