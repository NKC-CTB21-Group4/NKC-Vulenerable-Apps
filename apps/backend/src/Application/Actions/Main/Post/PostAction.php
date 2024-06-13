<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\User\UserRepository;

abstract class PostAction extends Action
{
  protected PostRepository $postRepository;
  protected UserRepository $userRepository;

  public function __construct(
    LoggerInterface $logger,
    PostRepository $postRepository,
    UserRepository $userRepository
  ){
    parent::__construct($logger);
    $this->postRepository = $postRepository;
    $this->userRepository = $userRepository;
  }
}
