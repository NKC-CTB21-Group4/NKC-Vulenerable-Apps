<?php

  declare(strict_types=1);

  namespace App\Application\Actions\Main\Admin\Post;

  use App\Application\Actions\Action;
  use App\Domain\Main\Post\Post;
  use Psr\Log\LoggerInterface;
  use App\Domain\Main\Post\PostRepository;

  abstract class AdminPostAction extends Action
  {
      protected PostRepository $postRepository;

      public function __construct(
          LoggerInterface $logger,
          PostRepository $postRepository
      ){
          parent::__construct($logger);
          $this->postRepository = $postRepository;
      }
  }
