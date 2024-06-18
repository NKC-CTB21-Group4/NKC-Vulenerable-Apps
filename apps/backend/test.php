<?php
// cli-config.php

// cli-config.php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use App\Domain\Main\User\UserRepository;
use App\Application\Actions\Main\User\ViewUserAction;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/vendor/autoload.php'; // Composer autoloader

// Assuming $settings and $dependencies are your settings and dependencies setup
$containerBuilder = new \DI\ContainerBuilder();

// Set up settings
$settings = require __DIR__ . '/app/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/app/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/app/repositories.php';
$repositories($containerBuilder);

$container = $containerBuilder->build();

// Retrieve EntityManager from DI container

new ViewUserAction($container->get(LoggerInterface::class),$container->get(UserRepository::class));

