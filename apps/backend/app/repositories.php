<?php

declare(strict_types=1);

use App\Domain\Main\User\UserRepository;
use App\Infrastructure\Persistence\Main\User\InMemoryUserRepository;
use App\Infrastructure\Persistence\Main\User\DatabaseUserRepository;
use Psr\Container\ContainerInterface;
use App\Domain\Challenges\Main\User\ChallengesUserRepository;
use App\Domain\Challenges\News\ChallengesNewsRepository;
use App\Domain\Challenges\Diary\ChallengesDiaryRepository;
use App\Domain\Challenges\Auth\ChallengesAuthTokenRepository;
use App\Infrastructure\Persistence\Challenges\Main\User\DatabaseChallengesUserRepository;
use App\Infrastructure\Persistence\Challenges\News\DatabaseChallengesNewsRepository;
use App\Infrastructure\Persistence\Challenges\Diary\DatabaseChallengesDiaryRepository;
use App\Infrastructure\Persistence\Challenges\Auth\DatabaseChallengesAuthTokenRepository;
use App\Infrastructure\Persistence\Challenges\Auth\ChallengesJwtService;



use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => function(ContainerInterface $c): UserRepository{
            return new DatabaseUserRepository($c->get(MainEntityManager::class));
        },
        ChallengesUserRepository::class => \DI\autowire(DatabaseChallengesUserRepository::class),
        ChallengesNewsRepository::class => \DI\autowire(DatabaseChallengesNewsRepository::class),
        ChallengesDiaryRepository::class => \DI\autowire(DatabaseChallengesDiaryRepository::class),
        ChallengesAuthTokenRepository::class => \DI\autowire(DatabaseChallengesAuthTokenRepository::class),
        ChallengesJwtMiddleware::class => \DI\create(ChallengesJwtMiddleware::class),
    ]);
};
