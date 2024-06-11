<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Post\PostRepository;

abstract class PostAction extends Action
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
