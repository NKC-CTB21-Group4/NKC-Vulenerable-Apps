<?php

declare(strict_types=1);

namespace App\Domain\Challenges\User;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ChallengesUserNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The user you requested does not exist.';
}
