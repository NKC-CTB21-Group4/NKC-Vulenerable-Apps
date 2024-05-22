<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Challenges\User;

use App\Domain\Challenges\User\ChallengesUser;
use App\Domain\Challenges\User\ChallengesUserRepository;
use App\Domain\Challenges\User\ChallengesUserNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;


class DatabaseChallengesUserRepository extends EntityRepository implements ChallengesUserRepository
{
    private EntityManager $entityManager;


    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(ChallengesUser::class));
    }

    /**
     * {@inheritdoc}
     */
    public function save(ChallengesUser $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    private function isDeleted($user)
    {
        return $user->getDeletedAt() !== null;  
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function findByUsername(string $username): ?ChallengesUser
    // {
    //     return $this->findOneBy(['username' => $username]);
    // }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function findByEmail(string $email): ?ChallengesUser
    // {
    //     return $this->findOneBy(['email' => $email]);
    // }

    // /**
    //  * {@inheritdoc}
    //  */
    public function findUserOfId(int $id): ChallengesUser
    {
        /** @var ChallengesUser $user */
        $user = parent::find((string) $id);

        if ($user === null || $this->isDeleted($user)) {
            throw new ChallengesUserNotFoundException();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_filter(parent::findAll(),function($user) {
            return !$this->isDeleted($user);
        });
    }

    // /**
    //  * {@inheritdoc}
    //  */
    public function createUser(ChallengesUser $user): ChallengesUser
    {
        $this->_em->persist($user);
        $this->_em->flush();
        return $user;
    }

    // /**
    //  * {@inheritdoc}
    //  */
    public function deleteUser(int $id): void
    {
        $user = $this->find($id);

        if ($user === null) {
            throw new ChallengesUserNotFoundException();
        }

        //論理削除のために、削除フラグを設定する例
        $user->setDeletedAt();
        $this->_em->flush();
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function updateUser(int $id, ChallengesUser $user): ChallengesUser
    // {
    //     $existingUser = $this->find($id);

    //     if ($existingUser === null) {
    //         throw new UserNotFoundException();
    //     }

    //     // 必要なプロパティを更新する
    //     $existingUser->setUsername($user->getUsername());
    //     $existingUser->setEmail($user->getEmail());
    //     // 他の必要なプロパティを追加

    //     $this->_em->flush();

    //     return $existingUser;
    // }

    
}
