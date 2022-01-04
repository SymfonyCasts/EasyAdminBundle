<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static User|Proxy find($criteria)
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy first(string $sortedField = 'id')
 * @method static User|Proxy last(string $sortedField = 'id')
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static User[]|Proxy[] all()
 * @method static User[]|Proxy[] findBy(array $attributes)
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method User|Proxy create($attributes = [])
 */
final class UserFactory extends ModelFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        // inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
        $this->passwordHasher = $passwordHasher;
    }

    public function promoteRole(string $role): self
    {
        $defaults = $this->getDefaults();

        $roles = array_merge($defaults['roles'], [
            $role
        ]);

        return $this->addState([
            'roles' => $roles,
        ]);
    }

    protected function getDefaults(): array
    {
        return [
            // add your default values here (https://github.com/zenstruck/foundry#model-factories)
            'email' => self::faker()->email(),
            'roles' => [
                'ROLE_USER',
            ],
            'plainPassword' => 'userpass',
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'avatar' => 'default.png',
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
             ->afterInstantiate(function(User $user) {
                 $hashedPassword = $this->passwordHasher
                     ->hashPassword($user, $user->getPlainPassword());

                 $user->setPassword($hashedPassword);

                 $fs = new Filesystem();
                 $newAvatarFilename = self::faker()->slug(2).'.png';
                 $fs->copy(
                     __DIR__.'/../../assets/images/'.$user->getAvatar(),
                     __DIR__.'/../../public/uploads/avatars/'.$newAvatarFilename
                 );
                 $user->setAvatar($newAvatarFilename);
             });
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
