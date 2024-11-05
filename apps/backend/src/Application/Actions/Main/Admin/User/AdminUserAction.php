<?php

  declare(strict_types=1);

  namespace App\Application\Actions\Main\Admin\User;

  use App\Application\Actions\Action;
  use App\Domain\Main\User\User;
  use Psr\Log\LoggerInterface;
  use App\Domain\Main\User\UserRepository;

  abstract class AdminUserAction extends Action
  {
      protected UserRepository $userRepository;

      public function __construct(
          LoggerInterface $logger,
          UserRepository $userRepository
      ){
          parent::__construct($logger);
          $this->userRepository = $userRepository;
      }

      protected function getUserFromToken():?User
      {
        return $this->request->getAttribute('token') ?? null;
      }
  }
