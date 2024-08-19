<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\Post;


use App\Domain\Main\Post\Post;
use App\Domain\Main\User\User;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\Post\PostNotFoundException;
use App\Domain\Main\User\UserPrivated;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabasePostRepository extends EntityRepository implements PostRepository
{
  private EntityManager $entityManager;

  public function __construct(EntityManager $entityManager)
  {
    $this->entityManager = $entityManager;
    parent::__construct($entityManager, $entityManager->getClassMetadata(Post::class));
  }

  public function save(Post $post):void
  {
    $this->_em->persist($post);
    $this->_em->flush();
  }

  private function isDeleted(Post $post):bool
  {
    return $post->getDeletedAt() !== null;
  }

  public function findAll():array
  {
      return array_filter(parent::findAll(),function($post) {
        return !$this->isDeleted($post);
    });
  }

  public function findPostOfUser(User $user):array{
    $queryBuilder = $this->createQueryBuilder('p')
        ->andWhere('p.author = :user')
        ->andWhere('p.deletedAt IS NULL')
        ->setParameter('user', $user)
        ->getQuery();

        return $queryBuilder->getResult();
  }

  public function findPostOfId(int $id): Post
  {

    $post = parent::find((string) $id);

    if ($post === null || $this->isDeleted($post)){
      throw new PostNotFoundException();
    }

    return $post;
  }

  public function findPublicPostOfId(int $postId, int $userId, bool $isMutualFollower): ?Post
{
    $userRepository = $this->_em->getRepository(User::class);
    $user = $userRepository->find($userId);

    if ($user === null) {
        throw new PostNotFoundException('User not found');
    }

    $isPrivate = $user->getIsPrivate();

    if ($isPrivate && !$isMutualFollower) {
        return null;
    }

    $post = parent::find((string) $postId);

    if ($post === null || $this->isDeleted($post)) {
        throw new PostNotFoundException();
    }

    return $post;
}


  public function create(Post $post):Post
  {
    $this->_em->persist($post);
    $this->_em->flush();
    return $post;
  }

  public function delete(int $id):void 
  {
    $post = $this->findPostOfId($id);
    $post->setDeletedAt();
    $this->_em->flush();
  }

  public function getRecommendedPosts():array
  {
    $posts = $this->findAll();

    /**アルゴリズムを実装 */
    return $posts;
  }

}