<?php

    declare(strict_types=1);

    namespace App\Application\Actions\Main\User;

    use App\Application\Actions\Action;
    use Psr\Log\LoggerInterface;
    use App\Domain\Main\User\UserRepository;
    use App\Infrastructure\Persistence\Main\Auth\JwtService;
    use App\Domain\Main\User\User;
    use Psr\Http\Message\ResponseInterface as Response;

    abstract class UserAction extends Action
    {
        protected UserRepository $userRepository;

        public function __construct(
            LoggerInterface $logger,
            UserRepository $userRepository,
            JwtService $jwtService
        ){
            parent::__construct($logger);
            $this->userRepository = $userRepository;
            $this->jwtService = $jwtService;
        }

        protected function getUserFromToken():?object
        {
            return (object)$this->request->getAttribute('token')['user'] ?? null;
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
?>