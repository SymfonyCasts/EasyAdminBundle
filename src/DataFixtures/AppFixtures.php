<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\TopicFactory;
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
        $this->loadTopics();
        $this->loadQuestions();
        $this->loadAnswers();

        $manager->flush();
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
                'avatar' => 'images/tisha.png',
            ])
            ->create();
        $this->setReference('tisha', $tisha);
    }

    private function loadTopics()
    {
        TopicFactory::new()->createMany(5);
    }

    private function loadQuestions()
    {
        QuestionFactory::new()->createMany(20);

        QuestionFactory::new()
            ->unpublished()
            ->createMany(5);
    }

    private function loadAnswers()
    {
        AnswerFactory::new()->createMany(100);
    }
}
