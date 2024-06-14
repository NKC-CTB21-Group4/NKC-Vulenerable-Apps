<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\Post;


use App\Domain\Main\Post\Post;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\Post\PostNotFoundException;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabasePostRepository extends EntityRepository implements PostRepository
{
  private EntityManager $entityManager;

  public function __construct(EntityManager $entityManager)
  {
    $this->entityManger = $entityManager;
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

  public function findPostOfId(int $id): Post
  {

    $post = parent::find((string) $id);

    if ($post === null || $this->isDeleted($post)){
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
    $post = findPostOfId($id);
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