<?php

    declare(strict_types=1);

    namespace App\Application\Actions\Main\User;

    use App\Applications\Actions\Action;
    use Psr\Log\LoggerInterface;
    use App\Domain\Main\User\UserRepository;

    abstract class useAction extends Action
    {
        protected UserRepository $userRepository;

        public function __construct(
            LoggerInterface $logger,
            UserRepository $userRepository;
        ){
            parent::__construct($logger);
            $this->userRepository = $userRepository;
        }
    }
?>