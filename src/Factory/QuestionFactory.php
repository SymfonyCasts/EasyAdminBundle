<?php

namespace App\Factory;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Question|Proxy findOrCreate(array $attributes)
 * @method static Question|Proxy random()
 * @method static Question[]|Proxy[] randomSet(int $number)
 * @method static Question[]|Proxy[] randomRange(int $min, int $max)
 * @method static QuestionRepository|RepositoryProxy repository()
 * @method Question|Proxy create($attributes = [])
 * @method Question[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class QuestionFactory extends ModelFactory
{
    public function unpublished(): self
    {
        return $this->addState(['isApproved' => false]);
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->realText(50),
            'question' => self::faker()->paragraphs(
                self::faker()->numberBetween(1, 4),
                true
            ),
            'createdAt' => self::faker()->dateTimeBetween('-100 days', '-1 minute'),
            'askedBy' => UserFactory::random(),
            'votes' => random_int(-20, 50),
            'topic' => TopicFactory::random(),
            'isApproved' => true,
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            //->afterInstantiate(function(Question $question) { });
        ;
    }

    protected static function getClass(): string
    {
        return Question::class;
    }
}
