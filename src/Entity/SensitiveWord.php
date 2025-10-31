<?php

namespace Tourze\SensitiveTextDetectBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\SensitiveTextDetectBundle\Repository\SensitiveWordRepository;

#[ORM\Entity(repositoryClass: SensitiveWordRepository::class)]
#[ORM\Table(name: 'forum_sensitive_word', options: ['comment' => '敏感词'])]
class SensitiveWord implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100, options: ['comment' => '敏感词'])]
    private ?string $word = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '状态（true=启用，false=禁用）'])]
    private ?bool $status = null;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '命中次数'])]
    private ?int $number = null;

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(?string $word): void
    {
        $this->word = $word;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): void
    {
        $this->status = $status;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }

    public function __toString(): string
    {
        return sprintf('%s #%s', 'SensitiveWord', $this->id ?? 'new');
    }
}
