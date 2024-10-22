<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\Post;

use App\Domain\Main\Post\Post;
use App\Domain\Main\User\User;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\Post\PostNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\Follow\FollowRepository;

class DatabasePostRepository extends EntityRepository implements PostRepository
{
    private EntityManager $entityManager;
    private UserRepository $userRepository;
    private FollowRepository $followRepository;

    // UserRepository, FollowRepository を追加
    public function __construct(
        EntityManager $entityManager, 
        UserRepository $userRepository, 
        FollowRepository $followRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->followRepository = $followRepository;
        parent::__construct($entityManager, $entityManager->getClassMetadata(Post::class));
    }

    public function save(Post $post): void
    {
        $this->_em->persist($post);
        $this->_em->flush();
    }

    private function isDeleted(Post $post): bool
    {
        return $post->getDeletedAt() !== null;
    }

    public function findAll(): array
    {
        return array_filter(parent::findAll(), function ($post) {
            return !$this->isDeleted($post);
        });
    }

    public function findAllPublicPosts(): array
    {
        return array_filter(parent::findAll(), function ($post) {
            $user = $post->getUser(); // 投稿者のユーザーを取得
            return !$this->isDeleted($post) && !$user->getIsPrivate(); // 投稿が削除されていないかつユーザーが公開の場合のみ表示
        });
    }

    public function findPostOfUser(User $user): array
    {
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

        if ($post === null || $this->isDeleted($post)) {
            throw new PostNotFoundException();
        }

        return $post;
    }

    public function findPublicPostOfId(int $followerId, int $userId, int $postId): ?Post
    {
        // ユーザーをリポジトリから取得
        $otherUser = $this->userRepository->find($userId);

        if ($otherUser === null) {
            throw new PostNotFoundException('User not found');
        }

        $isPrivate = $otherUser->getIsPrivate();

        // フォローリポジトリで相互フォロー確認
        $bothFollowcheck = $this->followRepository->bothFollowChecker($followerId, $userId);

        // プライベート投稿の場合、相互フォローが必要
        if ($isPrivate && !$bothFollowcheck) {
            return null;
        }

        // 投稿を取得
        $post = parent::find($postId);

        if ($post === null || $this->isDeleted($post)) {
            throw new PostNotFoundException();
        }

        return $post;
    }

    public function create(Post $post): Post
    {
        $this->_em->persist($post);
        $this->_em->flush();
        return $post;
    }

    public function delete(int $id): void
    {
        $post = $this->findPostOfId($id);
        $post->setDeletedAt();
        $this->_em->flush();
    }

    public function getRecommendedPosts(): array
    {
        $posts = $this->findAll();

        /**アルゴリズムを実装 */
        return $posts;
    }
}
