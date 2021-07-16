<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\QuestionFactory;
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
        $this->loadQuestions();
        $this->loadUsers($manager);

        $manager->flush();
    }

    private function loadQuestions()
    {
        QuestionFactory::new()->createMany(20);

        QuestionFactory::new()
            ->unpublished()
            ->createMany(5);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $adminUser = new User();
        $adminUser->setEmail('admin@symfonycasts.com');
        $adminUser->setRoles([
            'ROLE_SUPER_ADMIN',
        ]);
        $hashedPassword = $this->passwordHasher
            ->hashPassword($adminUser, 'adminpass');
        $adminUser->setPassword($hashedPassword);

        $manager->persist($adminUser);
    }
}
