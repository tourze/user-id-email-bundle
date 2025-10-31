<?php

declare(strict_types=1);

namespace Tourze\UserIDEmailBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;

/**
 * 邮箱身份管理控制器
 */
#[AdminCrud(routePath: '/user-id-email/email-identity', routeName: 'user_id_email_email_identity')]
final class EmailIdentityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EmailIdentity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('邮箱身份')
            ->setEntityLabelInPlural('邮箱身份')
            ->setSearchFields(['emailAddress', 'user.username'])
            ->setDefaultSort(['createTime' => 'DESC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            EmailField::new('emailAddress', '邮箱地址')
                ->setRequired(true)
                ->setHelp('用户的电子邮箱地址'),

            AssociationField::new('user', '关联用户')
                ->setRequired(false)
                ->setHelp('该邮箱身份关联的用户账户'),

            TextField::new('identityType', '身份类型')
                ->hideOnForm()
                ->setHelp('身份类型，固定为email'),

            TextField::new('identityValue', '身份值')
                ->hideOnForm()
                ->setHelp('身份值，即邮箱地址'),

            TextField::new('createdBy', '创建者')
                ->hideOnForm()
                ->setHelp('创建该记录的用户'),

            TextField::new('updatedBy', '更新者')
                ->hideOnForm()
                ->setHelp('最后更新该记录的用户'),

            DateTimeField::new('createTime', '创建时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            DateTimeField::new('updateTime', '更新时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('emailAddress')
            ->add('user')
            ->add('createTime')
            ->add('updateTime')
        ;
    }
}
