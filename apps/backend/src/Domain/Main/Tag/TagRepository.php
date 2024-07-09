<?php

declare(strict_types=1);

namespace App\Domain\Main\Tag;

interface TagRepository
{
   /**
     * @param Tag $tag
     * @return void 
     * @throws TagCreationFailedException;
     */
    public function create(Tag $tag): void;

    /**
     * @return Tag[]
     */
    public function findAll():array;

    /**
     * @param int $tagId
     * @throws TagNotFoundException
     * @return void
     */
    public function remove(int $id):void;


    /**
     * @param array $tagIds
     * @throws TagNotFoundException
     * @return Tag[]
     */
    public function findTagIds(array $tagIds):array;
}
