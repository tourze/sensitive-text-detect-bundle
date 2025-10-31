<?php

namespace Tourze\SensitiveTextDetectBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Tourze\SensitiveTextDetectBundle\Entity\SensitiveWord;

/**
 * @extends AbstractCrudController<SensitiveWord>
 */
#[AdminCrud(routePath: '/forum/sensitive-word', routeName: 'forum_sensitive_word')]
final class SensitiveWordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SensitiveWord::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('敏感词')
            ->setEntityLabelInPlural('敏感词列表')
            ->setPageTitle('index', '敏感词管理')
            ->setPageTitle('detail', '敏感词详情')
            ->setPageTitle('edit', '编辑敏感词')
            ->setPageTitle('new', '新建敏感词')
            ->setHelp('index', '管理论坛敏感词，用于内容审核和过滤')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['id', 'word'])
            ->setPaginatorPageSize(20)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->setMaxLength(9999);

        yield TextField::new('word', '敏感词')
            ->setRequired(true)
            ->setMaxLength(100)
            ->setHelp('需要过滤的敏感词语')
        ;

        yield BooleanField::new('status', '状态')
            ->setRequired(true)
            ->setHelp('控制敏感词是否生效')
        ;

        yield IntegerField::new('number', '命中次数')
            ->hideOnForm()
            ->setHelp('该敏感词被检测到的次数')
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->hideOnForm()
            ->hideOnIndex()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('word', '敏感词'))
            ->add(ChoiceFilter::new('status', '状态')->setChoices([
                '已启用' => true,
                '已禁用' => false,
            ]))
            ->add(NumericFilter::new('number', '命中次数'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
