# User ID Email Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Symfony Version](https://img.shields.io/badge/symfony-%3E%3D7.3-green.svg)](https://symfony.com/)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](#)

A Symfony bundle for managing email addresses as user identities with full ORM integration.

## Table of Contents

- [Features](#features)
- [Dependencies](#dependencies)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Advanced Usage](#advanced-usage)
- [API Reference](#api-reference)
- [Database Schema](#database-schema)
- [Security](#security)
- [License](#license)

## Features

- **Email Identity Management**: Store and manage email addresses as user identities
- **User Association**: Link email identities to Symfony User entities
- **Identity Service**: Unified service for finding identities by type and user
- **Doctrine Integration**: Full ORM support with repository pattern
- **Timestampable**: Automatic creation and update timestamps
- **Blameable**: Track who created/updated records
- **Snowflake ID**: Unique identifier generation
- **Validation**: Built-in email format and length validation

## Dependencies

This bundle requires:

- PHP 8.1 or higher
- Symfony 7.3 or higher
- Doctrine ORM 3.0 or higher
- doctrine/dbal ^4.0

Internal dependencies:
- tourze/doctrine-snowflake-bundle
- tourze/doctrine-timestamp-bundle
- tourze/doctrine-user-bundle
- tourze/user-id-bundle

## Installation

```bash
composer require tourze/user-id-email-bundle
```

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    Tourze\UserIDEmailBundle\UserIDEmailBundle::class => ['all' => true],
];
```

## Quick Start

### Basic Usage

```php
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;
use Tourze\UserIDEmailBundle\Service\UserIdentityEmailService;

// Create email identity
$emailIdentity = new EmailIdentity();
$emailIdentity->setEmailAddress('user@example.com');
$emailIdentity->setUser($user);

// Find identity by email
$identityService = $container->get(UserIdentityEmailService::class);
$identity = $identityService->findByType('email', 'user@example.com');

// Find all identities for a user
$identities = $identityService->findByUser($user);
```

## Advanced Usage

### Email Validation

The bundle automatically validates email addresses using Symfony's validation constraints:

```php
use Symfony\Component\Validator\Validator\ValidatorInterface;

// Validation is automatically applied
$emailIdentity = new EmailIdentity();
$emailIdentity->setEmailAddress('invalid-email'); // Will fail validation

$violations = $validator->validate($emailIdentity);
if (count($violations) > 0) {
    foreach ($violations as $violation) {
        echo $violation->getMessage();
    }
}
```

### Custom Repository Usage

```php
use Tourze\UserIDEmailBundle\Repository\EmailIdentityRepository;

// Get repository
$repository = $entityManager->getRepository(EmailIdentity::class);

// Custom queries
$emailIdentities = $repository->findBy([
    'emailAddress' => 'user@example.com'
]);

// Find by user
$userIdentities = $repository->findBy([
    'user' => $user
]);
```

### Batch Operations

```php
// Create multiple identities
$identities = [];
foreach ($emailAddresses as $email) {
    $identity = new EmailIdentity();
    $identity->setEmailAddress($email);
    $identity->setUser($user);
    $identities[] = $identity;
}

// Persist all at once
foreach ($identities as $identity) {
    $entityManager->persist($identity);
}
$entityManager->flush();
```

## API Reference

### EmailIdentity Entity

- `getEmailAddress()`: Get the email address
- `setEmailAddress(string $emailAddress)`: Set the email address
- `getUser()`: Get associated user
- `setUser(UserInterface $user)`: Set associated user
- `getIdentityValue()`: Get identity value (email address)
- `getIdentityType()`: Get identity type ('email')
- `getIdentityArray()`: Get identity as array format

### UserIdentityEmailService

- `findByType(string $type, string $value)`: Find identity by type and value
- `findByUser(UserInterface $user)`: Find all identities for a user

## Database Schema

The bundle creates a table `ims_user_identity_email` with the following structure:

- `id`: Primary key (Snowflake ID)
- `email_address`: Email address (VARCHAR 255)
- `user_id`: Foreign key to user table
- `create_time`: Creation timestamp
- `update_time`: Last update timestamp
- `create_user`: User who created the record
- `update_user`: User who last updated the record

## Security

### Email Validation

This bundle implements robust email validation to prevent security issues:

- **Format Validation**: Uses Symfony's `#[Assert\Email]` to ensure valid email format
- **Length Validation**: Prevents database overflow with `#[Assert\Length(max: 255)]`
- **SQL Injection Protection**: Uses Doctrine ORM parameterized queries
- **XSS Prevention**: Email addresses are properly escaped when displayed

### Data Protection

- **User Association**: Email identities are always linked to authenticated users
- **Audit Trail**: All changes are tracked with timestamps and user attribution
- **Access Control**: Repository methods respect Symfony's security context

### Best Practices

1. **Always validate email addresses** before persisting
2. **Use the service layer** instead of direct entity manipulation
3. **Implement proper authorization** checks in your controllers
4. **Sanitize email inputs** in forms and APIs
5. **Monitor for suspicious patterns** in email registration

### Reporting Security Issues

If you discover a security vulnerability, please send an email to 
security@example.com instead of using the issue tracker.

## License

This bundle is released under the MIT license. See the [LICENSE](LICENSE) file for details.