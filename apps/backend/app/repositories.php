<?php

declare(strict_types=1);

use App\Domain\Main\User\UserRepository;
use App\Domain\Main\Auth\AuthTokenRepository;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\Reaction\ReactionRepository;
use App\Domain\Main\DirectMessage\DirectMessageRepository;
use App\Domain\Main\Follow\FollowRepository;
use App\Infrastructure\Persistence\Main\User\InMemoryUserRepository;
use App\Infrastructure\Persistence\Main\User\DatabaseUserRepository;
use App\Infrastructure\Persistence\Main\Auth\DatabaseAuthTokenRepository;
use App\Infrastructure\Persistence\Main\Reaction\DatabaseReactionRepository;
use App\Infrastructure\Persistence\Main\DirectMessage\DatabaseDirectMessageRepository;
use App\Infrastructure\Persistence\Main\Post\DatabasePostRepository;
use App\Infrastructure\Persistence\Main\Follow\DatabaseFollowRepository;
use Psr\Container\ContainerInterface;
use App\Domain\Challenges\Main\User\ChallengesUserRepository;
use App\Domain\Challenges\News\ChallengesNewsRepository;
use App\Domain\Challenges\Diary\ChallengesDiaryRepository;
use App\Domain\Challenges\Auth\ChallengesAuthTokenRepository;
use App\Infrastructure\Persistence\Challenges\Main\User\DatabaseChallengesUserRepository;
use App\Infrastructure\Persistence\Challenges\News\DatabaseChallengesNewsRepository;
use App\Infrastructure\Persistence\Challenges\Diary\DatabaseChallengesDiaryRepository;
use App\Infrastructure\Persistence\Challenges\Auth\DatabaseChallengesAuthTokenRepository;



use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => function(ContainerInterface $c): UserRepository{
            return new DatabaseUserRepository($c->get(MainEntityManager::class));
        },
        AuthTokenRepository::class => function (ContainerInterface $c): AuthTokenRepository{
            return new DatabaseAuthTokenRepository($c->get(MainEntityManager::class));
        },
        JwtMiddleware::class => function (ContainerInterface $c): JwtMiddleware {
            return new JwtMiddleware($c->get(MainEntityManager::class));
        },
        PostRepository::class => function(ContainerInterface $c): PostRepository{
            return new DatabasePostRepository($c->get(MainEntityManager::class));
        },
        ReactionRepository::class => function(ContainerInterface $c):ReactionRepository{
            return new DatabaseReactionRepository($c->get(MainEntityManager::class));
        },
        DirectMessageRepository::class => function(ContainerInterface $c):DirectMessageRepository{
            return new DatabaseDirectMessageRepository($c->get(MainEntityManager::class),$c->get(UserRepository::class));
        },
        FollowRepository::class => function(ContainerInterface $c):FollowRepository{
            return new DatabaseFollowRepository($c->get(MainEntityManager::class));
        },
        ChallengesUserRepository::class => \DI\autowire(DatabaseChallengesUserRepository::class),
        ChallengesNewsRepository::class => \DI\autowire(DatabaseChallengesNewsRepository::class),
        ChallengesDiaryRepository::class => \DI\autowire(DatabaseChallengesDiaryRepository::class),
        ChallengesAuthTokenRepository::class => \DI\autowire(DatabaseChallengesAuthTokenRepository::class),
        ChallengesJwtMiddleware::class => \DI\create(ChallengesJwtMiddleware::class),
    ]);
};
