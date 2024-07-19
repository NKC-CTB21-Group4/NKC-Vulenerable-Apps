<?php

declare(strict_types=1);

namespace App\Domain\Main\User;

use App\Domain\DomainException\DomainRecordNotFoundException;

class UserPrivatedNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The user private flag was not provided.';
}