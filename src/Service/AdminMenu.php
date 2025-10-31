<?php

declare(strict_types=1);

namespace Tourze\SensitiveTextDetectBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\SensitiveTextDetectBundle\Entity\SensitiveWord;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        $forumManagement = $item->getChild('论坛管理');
        if (null === $forumManagement) {
            $forumManagement = $item->addChild('论坛管理');
        }

        $forumManagement
            ->addChild('敏感词管理')
            ->setUri($this->linkGenerator->getCurdListPage(SensitiveWord::class))
            ->setAttribute('icon', 'fas fa-exclamation-triangle')
        ;
    }
}
