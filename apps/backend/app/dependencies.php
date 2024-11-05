<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Doctrine\Common\Cache\FilesystemCache;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        EntityManager::class => function (ContainerInterface $c): EntityManager {
            /** @var array $settings */
            $settings = $c->get(SettingsInterface::class);
            $doctrineSettings = $settings->get('doctrine');

            $cache = new FilesystemCache('/tmp');
            $config = Setup::createAttributeMetadataConfiguration(
                $doctrineSettings['metadata_dirs']['challenges'],
                $doctrineSettings['dev_mode'],
                null,
                $cache
            );

            return EntityManager::create($doctrineSettings['connection']['challenges'], $config);
        },
        MainEntityManager::class => function (ContainerInterface $c): EntityManager {
            $settings = $c->get(SettingsInterface::class);
            $doctrineSettings = $settings->get('doctrine');

            $cache = new FilesystemCache('/tmp');
            $config = Setup::createAttributeMetadataConfiguration(
                $doctrineSettings['metadata_dirs']['main'],
                $doctrineSettings['dev_mode'],
                null,
                $cache
            );

            return EntityManager::create($doctrineSettings['connection']['main'], $config);
        }
    ]);
};
