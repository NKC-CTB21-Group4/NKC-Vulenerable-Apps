<?php

declare(strict_types=1);

namespace App\Domain\Main\User;

use App\Domain\DomainException\DomainRecordNotFoundException;

class UserSearchFailedException extends DomainRecordNotFoundException
{
    public $message = 'Failed to search users. Please try again.';
}
