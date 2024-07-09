<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Tag;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Tag\TagRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\User\User;
use App\Infrastructure\Persistence\Main\Auth\JwtService;
use Psr\Http\Message\ResponseInterface as Response;

abstract class TagAction extends Action
{
  protected TagRepository $tagRepository;
  protected UserRepository $userRepository;
  protected JwtService $jwtService;

  public function __construct(
    LoggerInterface $logger,
    PostRepository $tagRepository,
    UserRepository $userRepository,
    JwtService $jwtService
  ){
    parent::__construct($logger);
    $this->tagRepository = $tagRepository;
    $this->userRepository = $userRepository;
    $this->jwtService = $jwtService;
  }
}
