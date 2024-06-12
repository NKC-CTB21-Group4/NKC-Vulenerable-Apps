<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\User;

use App\Domain\Main\User\User;
use App\Domain\Main\User\UserNotFoundException;
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

}