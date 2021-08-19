<?php

namespace App\DataFixtures;

use App\Factory\QuestionFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers();
        $this->loadQuestions();

        $manager->flush();
    }

    private function loadQuestions()
    {
        QuestionFactory::new()->createMany(20);

        QuestionFactory::new()
            ->unpublished()
            ->createMany(5);
    }

    private function loadUsers()
    {
        $superAdmin = UserFactory::new()
            ->withAttributes([
                'email' => 'admin@symfonycasts.com',
                'password' => 'adminpass',
            ])
            ->promoteRole('ROLE_SUPER_ADMIN')
            ->create();
        $this->setReference('superadmin', $superAdmin);

        $admin = UserFactory::new()
            ->withAttributes([
                'email' => 'admin@symfony.com',
                'password' => 'adminpass',
            ])
            ->promoteRole('ROLE_ADMIN')
            ->create();
        $this->setReference('admin', $admin);

        $moderator = UserFactory::new()
            ->withAttributes([
                'email' => 'admin@example.com',
                'password' => 'adminpass',
            ])
            ->promoteRole('ROLE_MODERATOR')
            ->create();
        $this->setReference('moderator', $moderator);

        $tisha = UserFactory::new()
            ->withAttributes([
                'email' => 'tisha@symfonycasts.com',
                'password' => 'tishapass',
                'fullName' => 'Tisha',
            ])
            ->create();
        $this->setReference('tisha', $tisha);
    }
}
