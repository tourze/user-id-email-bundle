<?php

declare(strict_types=1);

namespace Tourze\UserIDEmailBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use Tourze\UserIDEmailBundle\Controller\Admin\EmailIdentityCrudController;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;

/**
 * 邮箱身份管理控制器测试
 * @internal
 */
#[CoversClass(EmailIdentityCrudController::class)]
#[RunTestsInSeparateProcesses]
class EmailIdentityCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerService(): EmailIdentityCrudController
    {
        $controller = self::getContainer()->get(EmailIdentityCrudController::class);
        self::assertInstanceOf(EmailIdentityCrudController::class, $controller);

        return $controller;
    }

    public function testGetEntityFqcn(): void
    {
        self::assertSame(EmailIdentity::class, EmailIdentityCrudController::getEntityFqcn());
    }

    public function testConfigureFields(): void
    {
        $controller = new EmailIdentityCrudController();
        $fields = $controller->configureFields('index');

        self::assertIsIterable($fields);

        // 将迭代器转换为数组以便更详细的测试
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray, '应该有字段配置');

        // 验证所有字段都是对象
        foreach ($fieldsArray as $field) {
            self::assertIsObject($field, '每个字段都应该是对象');
        }

        // 验证字段数量合理（应该有多个字段）
        self::assertGreaterThan(3, count($fieldsArray), '应该有多个字段');
    }

    public function testConfigureFieldsForDifferentPages(): void
    {
        $controller = new EmailIdentityCrudController();

        // 测试不同页面的字段配置
        $indexFields = $controller->configureFields('index');
        $detailFields = $controller->configureFields('detail');
        $newFields = $controller->configureFields('new');
        $editFields = $controller->configureFields('edit');

        self::assertIsIterable($indexFields);
        self::assertIsIterable($detailFields);
        self::assertIsIterable($newFields);
        self::assertIsIterable($editFields);
    }

    public function testValidationForRequiredFields(): void
    {
        $controller = new EmailIdentityCrudController();
        $fields = iterator_to_array($controller->configureFields('new'));

        // 测试字段配置是否正确
        self::assertNotEmpty($fields, '应该有字段配置');

        // 验证所有字段都是对象
        foreach ($fields as $field) {
            self::assertIsObject($field, '每个字段都应该是对象');
        }

        // 验证字段数量合理
        self::assertGreaterThan(2, count($fields), '应该有多个字段用于新建表单');

        // 验证必填字段：通过检查字段类型来验证邮箱字段存在
        $hasEmailField = false;
        foreach ($fields as $field) {
            if ($field instanceof EmailField) {
                $hasEmailField = true;
                break;
            }
        }
        self::assertTrue($hasEmailField, '应该有邮箱字段作为必填字段');
    }

    public function testConfigureFilters(): void
    {
        $controller = new EmailIdentityCrudController();

        // 验证方法返回类型
        $reflection = new \ReflectionMethod($controller, 'configureFilters');
        $returnType = $reflection->getReturnType();
        self::assertNotNull($returnType, 'configureFilters方法应该有返回类型');

        if ($returnType instanceof \ReflectionNamedType) {
            self::assertEquals('EasyCorp\Bundle\EasyAdminBundle\Config\Filters', $returnType->getName());
        }
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'id' => ['ID'];
        yield 'email' => ['邮箱地址'];
        yield 'user' => ['关联用户'];
        yield 'identity_type' => ['身份类型'];
        yield 'identity_value' => ['身份值'];
        yield 'creator' => ['创建者'];
        yield 'updater' => ['更新者'];
        yield 'created_at' => ['创建时间'];
        yield 'updated_at' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'email_address' => ['emailAddress'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'email_address' => ['emailAddress'];
    }

    /**
     * 重写基类方法，适配EmailIdentity实体的字段验证
     */

    /**
     * 测试验证错误 - 按照PHPStan建议添加
     */
    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();

        // 访问创建页面
        $crawler = $client->request('GET', $this->generateAdminUrl('new'));
        self::assertResponseIsSuccessful();

        // 查找表单并提交空表单
        $form = $crawler->selectButton('Create')->form();
        $crawler = $client->submit($form);

        // 验证返回状态码为422（验证错误）
        $this->assertResponseStatusCodeSame(422);

        // 验证错误消息包含必填字段验证错误（支持中英文）
        $errorElements = $crawler->filter('.invalid-feedback, .form-error-message, .alert-danger');
        if ($errorElements->count() > 0) {
            $errorText = $errorElements->text();
            // 检查中文或英文的必填验证错误信息
            $hasValidationError = str_contains($errorText, 'should not be blank')
                                || str_contains($errorText, '不能为空')
                                || str_contains($errorText, 'cannot be blank');
            self::assertTrue($hasValidationError, '邮箱地址字段应该有必填验证错误，实际错误信息: ' . $errorText);
        } else {
            // 备用检查：直接在页面内容中查找错误信息
            $responseContent = $client->getResponse()->getContent();
            self::assertNotFalse($responseContent);
            $hasValidationError = str_contains($responseContent, 'should not be blank')
                                || str_contains($responseContent, '不能为空')
                                || str_contains($responseContent, 'cannot be blank');
            self::assertTrue($hasValidationError, '页面应该包含必填字段验证错误信息');
        }
    }
}
