<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Domain\Challenges\User\ChallengesUserRepository;
use App\Domain\Challenges\News\ChallengesNewsRepository;
use App\Domain\Challenges\Diary\ChallengesDiaryRepository;
use App\Infrastructure\Persistence\Challenges\User\DatabaseChallengesUserRepository;
use App\Infrastructure\Persistence\Challenges\News\DatabaseChallengesNewsRepository;
use App\Infrastructure\Persistence\Challenges\Diary\DatabaseChallengesDiaryRepository;


use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        ChallengesUserRepository::class => \DI\autowire(DatabaseChallengesUserRepository::class),
        ChallengesNewsRepository::class => \DI\autowire(DatabaseChallengesNewsRepository::class),
        ChallengesDiaryRepository::class => \DI\autowire(DatabaseChallengesDiaryRepository::class),
    ]);
};
