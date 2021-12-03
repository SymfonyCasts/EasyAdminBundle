<?php

namespace App\Factory;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Answer|Proxy createOne(array $attributes = [])
 * @method static Answer[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Answer|Proxy find($criteria)
 * @method static Answer|Proxy findOrCreate(array $attributes)
 * @method static Answer|Proxy first(string $sortedField = 'id')
 * @method static Answer|Proxy last(string $sortedField = 'id')
 * @method static Answer|Proxy random(array $attributes = [])
 * @method static Answer|Proxy randomOrCreate(array $attributes = [])
 * @method static Answer[]|Proxy[] all()
 * @method static Answer[]|Proxy[] findBy(array $attributes)
 * @method static Answer[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Answer[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static AnswerRepository|RepositoryProxy repository()
 * @method Answer|Proxy create($attributes = [])
 */
final class AnswerFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'answer' => self::faker()->sentence(),
            'question' => QuestionFactory::random(),
            'createdAt' => self::faker()->dateTimeBetween('-100 days', '-1 minute'),
            'answeredBy' => UserFactory::random(),
            'votes' => random_int(-5, 10),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Answer $answer) {})
        ;
    }

    protected static function getClass(): string
    {
        return Answer::class;
    }
}
