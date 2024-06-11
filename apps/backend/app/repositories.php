<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Domain\Main\Post\PostRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Infrastructure\Persistence\Main\Post\DatabasePostRepository;

use App\Domain\Challenges\User\ChallengesUserRepository;
use App\Domain\Challenges\News\ChallengesNewsRepository;
use App\Domain\Challenges\Diary\ChallengesDiaryRepository;
use App\Domain\Challenges\Auth\ChallengesAuthTokenRepository;
use App\Infrastructure\Persistence\Challenges\User\DatabaseChallengesUserRepository;
use App\Infrastructure\Persistence\Challenges\News\DatabaseChallengesNewsRepository;
use App\Infrastructure\Persistence\Challenges\Diary\DatabaseChallengesDiaryRepository;
use App\Infrastructure\Persistence\Challenges\Auth\DatabaseChallengesAuthTokenRepository;
use App\Infrastructure\Persistence\Challenges\Auth\ChallengesJwtService;



use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        PostRepository::class => \DI\autowire(DatabasePostRepository::class),
        ChallengesUserRepository::class => \DI\autowire(DatabaseChallengesUserRepository::class),
        ChallengesNewsRepository::class => \DI\autowire(DatabaseChallengesNewsRepository::class),
        ChallengesDiaryRepository::class => \DI\autowire(DatabaseChallengesDiaryRepository::class),
        ChallengesAuthTokenRepository::class => \DI\autowire(DatabaseChallengesAuthTokenRepository::class),
        ChallengesJwtMiddleware::class => \DI\create(ChallengesJwtMiddleware::class),
    ]);
};
