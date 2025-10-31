<?php

namespace Tourze\UserIDEmailBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use Tourze\UserIDEmailBundle\Entity\EmailIdentity;

#[When(env: 'test')]
#[When(env: 'dev')]
class EmailIdentityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $emailIdentities = [
            'test@tourze.cn',
            'admin@tourze.cn',
            'user123@tourze.cn',
            'support@tourze.cn',
        ];

        foreach ($emailIdentities as $email) {
            $emailIdentity = new EmailIdentity();
            $emailIdentity->setEmailAddress($email);
            $manager->persist($emailIdentity);
        }

        $manager->flush();
    }
}
