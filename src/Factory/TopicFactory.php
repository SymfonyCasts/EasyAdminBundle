<?php

namespace App\Factory;

use App\Entity\Topic;
use App\Repository\TopicRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Topic|Proxy createOne(array $attributes = [])
 * @method static Topic[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Topic|Proxy find($criteria)
 * @method static Topic|Proxy findOrCreate(array $attributes)
 * @method static Topic|Proxy first(string $sortedField = 'id')
 * @method static Topic|Proxy last(string $sortedField = 'id')
 * @method static Topic|Proxy random(array $attributes = [])
 * @method static Topic|Proxy randomOrCreate(array $attributes = [])
 * @method static Topic[]|Proxy[] all()
 * @method static Topic[]|Proxy[] findBy(array $attributes)
 * @method static Topic[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Topic[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TopicRepository|RepositoryProxy repository()
 * @method Topic|Proxy create($attributes = [])
 */
final class TopicFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => ucfirst(self::faker()->words(3, true)),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Topic $topic) {})
        ;
    }

    protected static function getClass(): string
    {
        return Topic::class;
    }
}
