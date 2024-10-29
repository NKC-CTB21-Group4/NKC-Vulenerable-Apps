<?php

declare(strict_types=1);

namespace App\Domain\Main\Post;

use App\Domain\DomainException\DomainRecordNotFoundException;

class PostSearchFailedException extends DomainRecordNotFoundException
{
    public $message = 'Failed to search posts. Please try again.';
}
