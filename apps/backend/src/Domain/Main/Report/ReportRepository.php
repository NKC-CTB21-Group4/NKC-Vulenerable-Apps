<?php

declare(strict_types=1);

namespace App\Domain\Main\Report;

use App\Domain\Main\Post\Post;
use App\Domain\Main\Tag\Tag;

interface ReportRepository
{
   /**
     * @param Report $report
     * @return Report
     * @throws ReportCreationFailedException;
     */
    public function create(Report $report): Report;

    /**
     * @return Report[]
     */
    public function findAll():array;

    /**
     * @param int $reportId
     * @return Report
     * @throws ReportNotFoundException
     */
    public function findByReportId(int $reportId):Report;

    /**
     * @param Post $post
     * @return Report[]
     */
    public function findByPost(Post $post):array;

    /**
     * @return Report[]
     */
    public function getReportedPosts():array;

    /**
     * @param Tag $tag
     * @return void 
     */
    public function addTag(Tag $tag):void;

    /**
     * @param Tag $tag
     * @return void 
     */

     public function removeTag(Tag $tag):void;

     /**
      * @param int[] $tagIds
      * @return Report[]
      */

     public function findReportsWithTags(array $tagIds): array;

}
