<?php

namespace Tourze\SensitiveTextDetectBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use Tourze\SensitiveTextDetectBundle\Entity\SensitiveWord;

/**
 * @extends ServiceEntityRepository<SensitiveWord>
 */
#[AsRepository(entityClass: SensitiveWord::class)]
class SensitiveWordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SensitiveWord::class);
    }

    public function save(SensitiveWord $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SensitiveWord $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
