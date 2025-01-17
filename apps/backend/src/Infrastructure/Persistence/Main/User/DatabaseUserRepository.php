<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\User;

use App\Domain\Main\User\User;
use App\Domain\Main\User\UserNotFoundException;
use App\Domain\Main\User\UserSearchFailedException;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\User\UserDeleteFailedException;
use App\Domain\Main\Follow\FollowRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabaseUserRepository extends EntityRepository implements UserRepository
{
    private EntityManager $entityManager;
    private FollowRepository $followRepository;

    public function __construct(EntityManager $entityManager, FollowRepository $followRepository)
    {
        $this->entityManager = $entityManager;
        $this->followRepository = $followRepository;
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

    public function findAllUserIds(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('u.id')
                    ->from(User::class, 'u')
                    ->where('u.deletedAt IS NULL');

        return array_column($queryBuilder->getQuery()->getResult(), 'id');
    }

    public function createUser(User $user):User 
    {
        $this->save($user);
        return $user;
    }
//setisprivateflag
    public function setisprivateflag(int $userId, bool $isPrivate):User 
    {
        $user = $this->_em->getRepository(User::class)->find($userId);
        
        if ($user !== null) {
            $user->setIsPrivate($isPrivate);
            $this->save($user);
        }
        else {
            throw new UserNotFoundException();
        }
        return $user;
    }

    public function toggleIsPrivate(User $user):User
    {
        $user->setIsPrivate(!$user->getIsPrivate());
        $this->_em->persist($user);
        $this->_em->flush();
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

    public function updateUserAvatarPath(User $user):string {
        $user->updateAvatarPath();
        $this->_em->persist($user);
        $this->_em->flush();
        return $user->getAvatarPath();
    }
    
    public function search(array $searchParam):array {
        try {
            // 基本的なクエリ構築
            //$query = $this->createQueryBuilder('u');
            $dql = "select u FROM App\Domain\Main\User\User u WHERE 1=1";
      
            // キーワードによる検索
            if (!empty($searchParam['keyword'])) {
            //   $query->andWhere('LOWER(u.username) LIKE LOWER(:keyword)')
            //   ->setParameter('keyword', '%' . $searchParam['keyword'] . '%');
                $dql .= "AND LOWER(u.username) LIKE LOWER('%" . $searchParam['keyword'] . "%')";
            }
      
            // 特定ユーザーによるフィルタリング
            if (!empty($searchParam['userId'])) {
                // $query->andWhere('u.id = :user_id')
                // ->setParameter('user_id',$searchParam['userId']);
                $dql .= "AND u.id = " . $searchParam['userId'];
            }
      
            if (!empty($searchParam['onlyFromFollowedUser'])) {
                $followedUsers = $this->followRepository->findOfFollowed($searchParam['currentUserId']);
        
                // フォローしているユーザーのIDを配列として取得
                $followedUserIds = array_map(fn(User $user) => $user->getId(), $followedUsers);

                if (!empty($followedUserIds)) {
                    // $query->andWhere('u.id IN (:followedUserIds)')
                    //       ->setParameter('followedUserIds', $followedUserIds);
                    $dql .= "AND u.id IN (" . implode(',', $followedUserIds) . ")";
                } else {
                    // フォローしているユーザーがいない場合は結果を空に
                    //$query->andWhere('1 = 0');
                    $dql .= " AND 1 = 0";
                }
            }

            // クエリの実行と結果の取得
            //$users = $query->getQuery()->getResult();
            $query = $this->entityManager->createQuery($dql);
            $users = $query->getResult();
      
            if (empty($users)) {
                throw new UserNotFoundException();
            }
      
            return $users;
          } catch (Exception $e) {
              throw new UserSearchFailedException('An error occurred during the search.');
          }
    }
}