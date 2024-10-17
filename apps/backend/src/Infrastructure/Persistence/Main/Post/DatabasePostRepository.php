<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\Post;


use App\Domain\Main\Post\Post;
use App\Domain\Main\User\User;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\Post\PostNotFoundException;
use App\Domain\Main\Post\PostSearchFailedException;

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

  public function search(array $searchCriteria):array 
  {
    try {
      // 基本的なクエリ構築
      $query = $this->createQueryBuilder('p');

      // キーワードによる検索
      if (!empty($searchCriteria['keyword'])) {
        $query->andWhere('LOWER(p.content) LIKE LOWER(:keyword)')
        ->setParameter('keyword', '%' . $searchCriteria['keyword'] . '%');
      }

      // 特定ユーザーによるフィルタリング
      if (!empty($searchCriteria['authorId'])) {
          $query->andWhere('p.author = :author_id')
          ->setParameter('author_id',$searchCriteria['authorId']);
      }

      if (!empty($searchCriteria['authorName'])) {
        $query->join('p.author', 'user')
        ->andWhere('user.username LIKE :authorName')
        ->setParameter('authorName', '%' . $searchCriteria['authorName'] . '%');
      }
    
    
      // 日付範囲によるフィルタリング 次回ここから
      if (!empty($searchCriteria['dateFrom'])) {
          $query->andWhere('p.createdAt >= :dateFrom')
          ->setParameter('dateFrom',new \DateTime($searchCriteria['dateFrom']));
      }
      if (!empty($searchCriteria['dateTo'])) {
          $query->andWhere('p.createdAt <= :dateTo')
          ->setParameter('dateTo',new \DateTime($searchCriteria['dateTo']));
      }

      // onlyFromFollowedUser オプションの処理
      if (!empty($searchCriteria['onlyFromFollowedUser']) && $searchCriteria['onlyFromFollowedUser'] === true) {
          $followedUserIds = $this->getFollowedUserIds($searchCriteria['currentUserId']);
          $query->whereIn('user_id', $followedUserIds);
      }

      // ソート順
      if (!empty($searchCriteria['sortBy'])) {
          $query->orderBy($searchCriteria['sortBy'], 'desc');
      }

      // クエリの実行と結果の取得
      $posts = $query->getQuery()->getResult();

      if (empty($posts)) {
          throw new PostNotFoundException();
      }

      return $posts;
    } catch (Exception $e) {
        throw new PostSearchFailedException('An error occurred during the search.');
    }
  }
}