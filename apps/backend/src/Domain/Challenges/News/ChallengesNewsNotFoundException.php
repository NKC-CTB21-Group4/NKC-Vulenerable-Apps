<?php

declare(strict_types=1);

namespace App\Domain\Challenges\News;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ChallengesNewsNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The news you requested does not exist.';
}
