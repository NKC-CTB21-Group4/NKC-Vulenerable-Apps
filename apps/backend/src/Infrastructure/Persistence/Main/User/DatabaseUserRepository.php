<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\User;

use App\Domain\Main\User\User;
use App\Domain\Main\User\UserNotFoundException;
use App\Domain\Main\User\UserSearchFailedException;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\User\UserDeleteFailedException;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabaseUserRepository extends EntityRepository implements UserRepository
{
    private EntityManager $entityManager;


    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(User::class));
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findAll(): array
    {
        return array_filter(parent::findAll(),function($user) {
            return !$this->isdeletedAtSet($user);
        });
    }

    public function findUserOfId(int $id): User
    {
        $user = parent::find($id);

        if ($user === null || $this->isdeletedAtSet($user)) {
          throw new UserNotFoundException();
        }

        return $user;
    }

    public function createUser(User $user):User 
    {
        $this->save($user);
        return $user;
    }

    private function isdeletedAtSet(User $user): bool
    {
        return $user->getDeletedAt() !== NULL;
    }

    public function deleteUser(int $id) :void 
    {
        $user = $this->findUserOfId($id);

        /*$userがnullの場合 */
        if($user === null){
            throw new UserNotFoundException();
        }
        elseif($this->isdeletedAtSet($user)){
            /*値あり（既に削除されている） */
            throw new UserDeleteFailedException();
        }
        else{
            /*null（削除されていない） */
            $user->setDeletedAt(new \DateTime());
        }

        $this->_em->flush();
    }
    public function findByEmailAndPassword(string $email, string $password): User {

        $user = parent::findOneBy(['email' => $email]);
        if ($user === null || $this->isdeletedAtSet($user)) {
            throw new UserNotFoundException();
        }

        if ($email !== $user->getEmail() ||  !password_verify($password,$user->getSecurePassword())) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function updateUser(int $id,array $userInfo):User{
        $user = $this->findUserOfId($id);
        if ($user === null || $this->isdeletedAtSet($user)) {
            throw new UserNotFoundException();
        }
        $user->fromArray($userInfo);
        $this->_em->persist($user);
        $this->_em->flush();
        return $user;
    }

    public function search($searchParam):array {
        try {
            // 基本的なクエリ構築
            $query = $this->createQueryBuilder('u');
      
            // キーワードによる検索
            if (!empty($searchParam['keyword'])) {
              $query->andWhere('LOWER(u.username) LIKE LOWER(:keyword)')
              ->setParameter('keyword', '%' . $searchParam['keyword'] . '%');
            }
      
            // 特定ユーザーによるフィルタリング
            if (!empty($searchParam['userId'])) {
                $query->andWhere('u.id = :user_id')
                ->setParameter('user_id',$searchParam['userId']);
            }
      
            // onlyFromFollowedUser オプションの処理 
            // if (!empty($searchCriteria['onlyFromFollowedUser']) && $searchCriteria['onlyFromFollowedUser'] === true) {
            //     $followedUserIds = $this->getFollowedUserIds($searchCriteria['currentUserId']);
            //     $query->whereIn('user_id', $followedUserIds);
            // }

            // クエリの実行と結果の取得
            $users = $query->getQuery()->getResult();
      
            if (empty($users)) {
                throw new UserNotFoundException();
            }
      
            return $users;
          } catch (Exception $e) {
              throw new UserSearchFailedException('An error occurred during the search.');
          }
    }
}