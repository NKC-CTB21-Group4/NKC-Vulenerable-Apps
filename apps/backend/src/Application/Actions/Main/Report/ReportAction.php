<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Report;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Report\ReportRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\Tag\TagRepository;
use App\Domain\Main\User\User;
use App\Infrastructure\Persistence\Main\Auth\JwtService;
use Psr\Http\Message\ResponseInterface as Response;

abstract class ReportAction extends Action
{
  protected ReportRepository $reportRepository;
  protected UserRepository $userRepository;
  protected PostRepository $postRepository;
  protected JwtService $jwtService;

  public function __construct(
    LoggerInterface $logger,
    PostRepository $postRepository,
    UserRepository $userRepository,
    ReportRepository $reportRepository,
    TagRepository $tagRepository,
    JwtService $jwtService
  ){
    parent::__construct($logger);
    $this->reportRepository = $reportRepository;
    $this->userRepository = $userRepository;
    $this->postRepository = $postRepository;
    $this->tagRepository = $tagRepository;
    $this->jwtService = $jwtService;
  }

  protected function getUserFromToken():?object
  {
      return (object)$this->request->getAttribute('token')['user'] ?? null;
  }

  protected function getUserFromHeader(): ?object
    {
      $authHeader = $this->request->getHeader('Authorization');

      if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader[0], $matches)) {
          return null;
      }

      $token = $matches[1];
      $decoded = $this->jwtService->validateToken($token);

      if (!$decoded) {
          return null;
      }
      $user = $decoded['user'];
      return $user;
    }
    protected function checkUserAuthorization(?object $user): ?User
    {
      if($user === null)return null;
        $userId = (int) $this->resolveArg('userId');
        $user = $this->userRepository->findUserOfId($user->id);
        if ($user->getId() !== $userId) {
            return null;
        }
        return $user;
    }
}
