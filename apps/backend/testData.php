<?php
// cli-config.php

// cli-config.php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\Tag\Tag;
use App\Domain\Main\Tag\TagRepository;
use App\Domain\Main\Report\Report;
use App\Domain\Main\Report\ReportRepository;

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
$entityManager = $container->get(EntityManager::class);
$mainEntityManager = $container->get(MainEntityManager::class);

$tagRepository = $container->get(TagRepository::class);
$reportRepository = $container->get(ReportRepository::class);
$postRepository = $container->get(PostRepository::class);
$userRepository = $container->get(UserRepository::class);

$tagRepository->create(new Tag("差別的な発言"));
$tagRepository->create(new Tag("暴力的な発言"));
$tagRepository->create(new Tag("スパム"));
$tagRepository->create(new Tag("自殺や自傷行為"));

createReport(1, 2,[1], '差別的な発言が含まれている');
createReport(2, 3,[2], '暴力的な言動が見られる');
createReport(3, 1,[3], 'スパムとして疑わしい');
createReport(4, 2,[4], '自殺や自傷行為を促す内容');
createReport(5, 4,[1], '差別的な発言が含まれている');
createReport(6, 1,[2], '暴力的な言動が見られる');
createReport(8, 4,[4], '自殺や自傷行為を促す内容');
createReport(9, 2,[1], '差別的な発言が含まれている');
createReport(1, 3,[2], '暴力的な言動が見られる');

function createReport($postid,$userid,$tagids,$reason){
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
  $tagRepository = $container->get(TagRepository::class);
  $reportRepository = $container->get(ReportRepository::class);
  $postRepository = $container->get(PostRepository::class);
  $userRepository = $container->get(UserRepository::class);
  $post = $postRepository->findPostOfId($postid);
  $user = $userRepository->findUserOfId($userid);
  $tags = $tagRepository->findTagIds($tagids);
  $reportRepository->create(new Report($post,$user,$tags,$reason));
}


