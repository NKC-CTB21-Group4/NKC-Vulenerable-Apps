<?php

declare(strict_types=1);

namespace App\Domain\Challenges\Diary;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ChallengesDiaryNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The diary you requested does not exist.';
}
