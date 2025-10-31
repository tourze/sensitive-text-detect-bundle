<?php

namespace Tourze\SensitiveTextDetectBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Tourze\SensitiveTextDetectBundle\Entity\SensitiveWord;

class SensitiveWordFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sensitiveWords = [
            ['word' => '违禁词1', 'number' => 10],
            ['word' => '违禁词2', 'number' => 5],
            ['word' => '敏感词汇', 'number' => 15],
            ['word' => '不当内容', 'number' => 8],
            ['word' => '垃圾信息', 'number' => 20],
        ];

        foreach ($sensitiveWords as $data) {
            $sensitiveWord = new SensitiveWord();
            $sensitiveWord->setWord($data['word']);
            $sensitiveWord->setStatus(true);
            $sensitiveWord->setNumber($data['number']);
            $manager->persist($sensitiveWord);
        }

        $manager->flush();
    }
}
