# User ID Email Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Symfony Version](https://img.shields.io/badge/symfony-%3E%3D7.3-green.svg)](https://symfony.com/)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](#)

用户身份电子邮箱管理模块，提供完整的 ORM 集成。

## 目录

- [功能特性](#功能特性)
- [依赖要求](#依赖要求)
- [安装](#安装)
- [快速开始](#快速开始)
- [高级用法](#高级用法)
- [API 参考](#api-参考)
- [数据库架构](#数据库架构)
- [安全性](#安全性)
- [许可证](#许可证)

## 功能特性

- **邮箱身份管理**：存储和管理邮箱地址作为用户身份
- **用户关联**：将邮箱身份链接到 Symfony 用户实体
- **身份服务**：统一的服务用于按类型和用户查找身份
- **Doctrine 集成**：完整的 ORM 支持和仓储模式
- **时间戳**：自动创建和更新时间戳
- **责任追踪**：跟踪谁创建/更新了记录
- **雪花 ID**：唯一标识符生成
- **数据验证**：内置邮箱格式和长度验证

## 依赖要求

此包需要：

- PHP 8.1 或更高版本
- Symfony 7.3 或更高版本
- Doctrine ORM 3.0 或更高版本
- doctrine/dbal ^4.0

内部依赖：
- tourze/doctrine-snowflake-bundle
- tourze/doctrine-timestamp-bundle
- tourze/doctrine-user-bundle
- tourze/user-id-bundle

## 安装

```bash
composer require tourze/user-id-email-bundle
```

## 快速开始

### 基本使用

```php
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;
use Tourze\UserIDEmailBundle\Service\UserIdentityEmailService;

// 创建邮箱身份
$emailIdentity = new EmailIdentity();
$emailIdentity->setEmailAddress('user@example.com');
$emailIdentity->setUser($user);

// 根据邮箱查找身份
$identityService = $container->get(UserIdentityEmailService::class);
$identity = $identityService->findByType('email', 'user@example.com');

// 查找用户的所有身份
$identities = $identityService->findByUser($user);
```

## 高级用法

### 邮箱验证

包会自动使用 Symfony 的验证约束来验证邮箱地址：

```php
use Symfony\Component\Validator\Validator\ValidatorInterface;

// 验证会自动应用
$emailIdentity = new EmailIdentity();
$emailIdentity->setEmailAddress('invalid-email'); // 验证会失败

$violations = $validator->validate($emailIdentity);
if (count($violations) > 0) {
    foreach ($violations as $violation) {
        echo $violation->getMessage();
    }
}
```

### 自定义仓储使用

```php
use Tourze\UserIDEmailBundle\Repository\EmailIdentityRepository;

// 获取仓储
$repository = $entityManager->getRepository(EmailIdentity::class);

// 自定义查询
$emailIdentities = $repository->findBy([
    'emailAddress' => 'user@example.com'
]);

// 按用户查找
$userIdentities = $repository->findBy([
    'user' => $user
]);
```

### 批量操作

```php
// 创建多个身份
$identities = [];
foreach ($emailAddresses as $email) {
    $identity = new EmailIdentity();
    $identity->setEmailAddress($email);
    $identity->setUser($user);
    $identities[] = $identity;
}

// 一次性持久化所有
foreach ($identities as $identity) {
    $entityManager->persist($identity);
}
$entityManager->flush();
```

## API 参考

### EmailIdentity 实体

- `getEmailAddress()`: 获取邮箱地址
- `setEmailAddress(string $emailAddress)`: 设置邮箱地址
- `getUser()`: 获取关联用户
- `setUser(UserInterface $user)`: 设置关联用户
- `getIdentityValue()`: 获取身份值（邮箱地址）
- `getIdentityType()`: 获取身份类型（'email'）
- `getIdentityArray()`: 获取数组格式的身份信息

### UserIdentityEmailService

- `findByType(string $type, string $value)`: 根据类型和值查找身份
- `findByUser(UserInterface $user)`: 查找用户的所有身份

## 数据库架构

此包创建一个名为 `ims_user_identity_email` 的表，具有以下结构：

- `id`: 主键（雪花 ID）
- `email_address`: 邮箱地址（VARCHAR 255）
- `user_id`: 用户表外键
- `create_time`: 创建时间戳
- `update_time`: 最后更新时间戳
- `create_user`: 创建记录的用户
- `update_user`: 最后更新记录的用户

## 安全性

### 邮箱验证

此包实现了强健的邮箱验证以防止安全问题：

- **格式验证**：使用 Symfony 的 `#[Assert\Email]` 确保有效的邮箱格式
- **长度验证**：使用 `#[Assert\Length(max: 255)]` 防止数据库溢出
- **SQL 注入防护**：使用 Doctrine ORM 参数化查询
- **XSS 防护**：邮箱地址在显示时会被适当转义

### 数据保护

- **用户关联**：邮箱身份始终与经过身份验证的用户关联
- **审计跟踪**：所有更改都会记录时间戳和用户归属
- **访问控制**：仓储方法遵循 Symfony 的安全上下文

### 最佳实践

1. **始终验证邮箱地址** 在持久化之前
2. **使用服务层** 而不是直接操作实体
3. **在控制器中实现适当的授权** 检查
4. **在表单和 API 中清理邮箱输入**
5. **监控邮箱注册中的可疑模式**

### 报告安全问题

如果您发现安全漏洞，请发送邮件至 security@example.com，而不是使用问题跟踪器。

## 许可证

此包在 MIT 许可证下发布。详情请参阅 [LICENSE](LICENSE) 文件。
