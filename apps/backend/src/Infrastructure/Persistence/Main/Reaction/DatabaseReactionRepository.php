<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\Reaction;

use App\Domain\Main\Reaction\Reaction;
use App\Domain\Main\Reaction\ReactionRepository;
use App\Domain\Main\User\User;
use App\Domain\Main\Post\Post;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabaseReactionRepository extends EntityRepository implements ReactionRepository
{
  private EntityManager $entityManager;

  public function __construct(EntityManager $entityManager)
  {
    $this->entityManager = $entityManager;
    parent::__construct($entityManager,$entityManager->getClassMetadata(Reaction::class));
  }

  public function save(Reaction $reaction):bool
  {
    $this->_em->persist($reaction);
    $this->_em->flush();
    return $reaction->isFav();
  }

  public function getFavsCountByPostId(int $id): int
  {
    $reactions =  parent::findBy(['post' => $id, 'isFav' => true]);
    return count($reactions);
  }

  public function togglePostFav(User $user,Post $post):bool
  {
    $reaction = $this->findReactionByUserIdAndPostId($user->getId(),$post->getId());
    $result = !$reaction ? $this->createReaction(new Reaction($user,$post,true)) : $this->save($reaction->toggleFav());
    return $result;
  }

  public function findReactionByUserIdAndPostId(int $userId,int $postId)
  {
    $qb = $this->entityManager->createQueryBuilder();
        
        // andX を使用して条件を組み合わせる
        $andX = $qb->expr()->andX(
            $qb->expr()->eq('r.user', ':userId'),
            $qb->expr()->eq('r.post', ':postId')
        );

        $qb->select('r')
           ->from(Reaction::class, 'r')
           ->where($andX)
           ->setParameter('userId', $userId)
           ->setParameter('postId', $postId);
    
    return $qb->getQuery()->getOneOrNullResult();
  }

  private function createReaction(Reaction $reaction):bool
  {
    $this->save($reaction);
    return $reaction->isFav();
  }

}