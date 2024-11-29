<?php
    declare(strict_types=1);

    namespace App\Application\Actions\Main\User;

    use Psr\Http\Message\ResponseInterface as Response;
    use App\Domain\Main\User\UserNotFoundException;
    use App\Domain\Main\User\UserSearchFailedException;
    
    class SearchUsersAction extends UserAction
    {
        protected function action(): Response
        {
            $queryParams = $this->request->getQueryParams();

            // 検索条件を設定
            $searchCriteria = [];
            
            if (!empty($queryParams['userId'])) {
              $searchCriteria['userId'] = (int)$queryParams['userId'];
            }

            if (!empty($queryParams['keyword'])){
              $searchCriteria['keyword'] = $queryParams['keyword'];
            }

            if (!empty($queryParams['onlyFromFollowedUser'])) {
              $queryParams['onlyFromFollowedUser'] = true;
      
              $userFromToken = $this->getUserFromHeader();
              
              $queryParams['currentUserId'] = $userFromToken->getId();
            }
            
            // 検索実行
            $users = $this->userRepository->search($queryParams);

            $this->logger->info("user search was executed.");

            // 検索結果を返す
            return $this->respondWithData($users);
        }
    }
?>